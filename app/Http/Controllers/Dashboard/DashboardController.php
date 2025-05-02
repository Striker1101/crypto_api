<?php

namespace App\Http\Controllers;

use App\Models\AdminBalance;
use App\Models\Attachment;
use App\Models\BasicSetting;
use App\Models\DefaultStock;
use App\Models\Deposit;
use App\Models\Fund;
use App\Models\FundLog;
use App\Models\GeneralSetting;
use App\Models\ManualBank;
use App\Models\ManualCrypto;
use App\Models\ManualFund;
use App\Models\ManualFundLog;
use App\Models\ManualPayment;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\RebeatLog;
use App\Models\Reference;
use App\Models\Repeat;
use App\Models\Stock;
use App\Models\Task;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use League\Flysystem\Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NotifyComposeRequest;

class DashboardController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['general'] = GeneralSetting::first();
        $this->data['site_title'] = $this->data['general']->title;
        $this->data['basic'] = BasicSetting::first();
    }

    protected function get_notification($id, $data)
    {
        //message
        $data['message'] = Notification::where('user_id', $id)
            ->where('gene', 'message')
            ->first();
        $data['message_count'] = Notification::where('user_id', $id)
            ->where('gene', 'message')
            ->count();

        //warning
        $data['warning'] = Notification::where('user_id', $id)
            ->where('gene', 'warning')
            ->first();
        $data['warning_count'] = Notification::where('user_id', $id)
            ->where('gene', 'warning')
            ->count();

    }

    public function getDashboard()
    {
        $user = User::find(Auth::user()->id);
        // Eager load user's stocks and their associated default stocks
        $user->load('stocks.stocks');

        // Access user's stocks with their associated default stocks
        $userStocks = $user->stocks;


        $defaultStocks = DefaultStock::all();

        //know which defaultstock user already have and add status = true else false to that stock
        // Map default stocks to include status based on user's stocks
        $defaultStocks->transform(function ($defaultStock) use ($userStocks) {

            $userHasStock = $userStocks->where('id', $defaultStock->id)->first();

            $defaultStock->status = $userHasStock ? true : false;
            return $defaultStock;
        });


        //extra
        $this->data['member'] = User::findOrFail(Auth::user()->id);
        $this->data['namew'] = $this->data['member']->ID_Number;

        //get notification
        $this->get_notification(Auth::user()->id, $this->data);
        return view('user.dashboard', array_merge($this->data, [
            'page_title' => "User Dashboard",
            'member' => User::findOrFail(Auth::user()->id),
            'namew' => $this->data['member']->ID_Number,
            'stocks' => $defaultStocks,
            'userStocks' => $userStocks,
            'withdrawalcnt' => DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']])

        ]));
    }

    public function userBuyAndSell()
    {
        $user = auth()->user();
        $userStocks = Stock::where('user_id', Auth::user()->id)->get();
        $this->data['member'] = User::findOrFail($user->id);
        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);
        $this->data['userStocks'] = $userStocks;
        $plans = Plan::with('compound')->where('status', true)->get();
        $method = ManualPayment::where("title", "Upgrade")->first();
        return view('user.buy-and-sell', array_merge($this->data, [
            'page_title' => "Buy And Sell Stocks And Crypto",
            'plans' => $plans,
            'upgrade_id' => $method
        ]));
    }

    private function generateRandomWallet()
    {
        return bin2hex(random_bytes(8));
    }

    public function userNotify()
    {
        $user = auth()->user();

        $id = request()->query('id');


        if ($id)
        {
            return redirect()->route('user-notification-details', ['id' => $id]);
        }

        $this->data['member'] = User::findOrFail($user->id);
        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);
        return view("notification.index", array_merge($this->data, [
            'attachments' => $user->attachments,
            'attachments_count' => $user->attachments->count(),
            'page_title' => "Inbox",

        ]));
    }

    public function updateTaskStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->status = $request->input('status');

        $task->save();

        session()->flash('message', 'Task has been updated successfully');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');


        return redirect()->back();
    }

    public function updateNotificationStatus(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->status = $request->input('status');
        $notification->save();

        session()->flash('message', 'Notification has been updated successfully');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');


        return redirect()->back();
    }

    public function deleteTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        session()->flash('message', 'Task has been deleted');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }

    public function userNotifyComposeSubmit(NotifyComposeRequest $request)
    {
        $data['title'] = $request->subject;
        $data['status'] = false;
        $data['gene'] = 'message';
        $data['type'] = 'sent';
        $data['icon'] = 'entypo-arrow-bold-up pull-right';
        $data['tag'] = '';
        $data['content'] = $request->sample_wysiwyg;
        $data['user_id'] = auth()->user()->id;

        $notification = Notification::create($data);

        if ($request->hasFile('attachments'))
        {
            foreach ($request->file('attachments') as $file)
            {
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $size = $file->getSize(); // Get the size before moving the file
                $location = 'assets/attachments/';

                // Move the file to the desired location
                $file->move(public_path($location), $filename);

                // Store file information in the database
                Attachment::create([
                    'doc' => $filename,
                    'notify_id' => $notification->id,
                    'user_id' => auth()->id(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $size, // Use the size retrieved earlier
                ]);
            }
        }

        session()->flash('message', 'Message sent Successfully, you would receive fedback from our support teams on' . $this->data['general']->email);
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }


    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->type = 'trash';
        $notification->save();

        session()->flash('message', $notification->title . ' has been moved to trash');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    public function userNotifyDetails($id)
    {
        $notification = Notification::with('attachments')->find($id);
        if (!$notification)
        {
            // Handle case where the notification is not found
            return redirect()->back()->with('error', 'Notification not found.');
        }


        $notification->status = true;
        $notification->save();

        $user = auth()->user();
        $attachments = $notification->attachments;
        $attachments_count = $attachments->count();

        $this->data['member'] = User::findOrFail($user->id);

        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);
        return view('notification.preview', array_merge($this->data, [
            'page_title' => "Inbox",
            'notification' => $notification,
            'attachments_count' => $attachments_count,
            'attachments' => $attachments,
        ]));
    }
    public function userNotifyCompose()
    {
        $user = auth()->user();
        $this->data['member'] = User::findOrFail($user->id);
        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);
        return view('notification.compose', array_merge($this->data, [
            'page_title' => "Notification",

        ]));
    }



    protected function separateString($input)
    {
        // Split the string by the colon followed by a space
        $parts = explode(' : ', $input);

        // Check if the split resulted in two parts
        if (count($parts) === 2)
        {
            $first = $parts[0];
            $second = $parts[1];
            return [
                'id' => $first,
                'port' => $second
            ];
        } else
        {
            // Handle error case if the input string format is incorrect
            return [
                'id' => null,
                'port' => null
            ];
        }
    }

    public function userTask()
    {
        $user = auth()->user();
        $this->data['member'] = User::findOrFail($user->id);

        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);

        return view('user.task', array_merge($this->data, [
            'page_title' => "Inbox",
            'tasks' => Task::where('user_id', $user->id)->get(),
        ]));
    }

    public function userCalender()
    {
        $user = auth()->user();
        $this->data['member'] = User::findOrFail($user->id);

        $this->data['namew'] = $this->data['member']->ID_Number;
        $this->data['withdrawalcnt'] = DB::select("SELECT * FROM users WHERE ID_Number = ?", [$this->data['namew']]);

        return view('user.calender', array_merge($this->data, [
            'page_title' => "Inbox",
        ]));
    }

    public function userTaskStore(Request $request)
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $data['title'] = 'none';
        $data['content'] = $request->content;
        $data['status'] = false;
        $data['percent'] = 0;

        Task::create($data);
        return redirect()->back();
    }
    public function UserLiquidate(Request $request)
    {

        // Separate the options into id and port
        $result_from = $this->separateString($request['fromOption']);
        $data['from-amount'] = $request['from-number'];
        $data['from-id'] = $result_from['id'];
        $data['from-port'] = $result_from['port'];

        $result_to = $this->separateString($request['toOption']);
        $data['to-amount'] = $request['to-number'];
        $data['to-id'] = $result_to['id'];
        $data['to-port'] = $result_to['port'];
        // dd($data);

        // Handle the 'from' port operation
        if ($data['from-port'] == "user")
        {
            $user = User::find($data['from-id']);
            if ($user->amount < $data['from-amount'])
            {
                return redirect()->back()->withErrors('Insufficient Funds');
            }

            if ($user)
            {
                $user->amount -= $data['from-amount'];
                $user->save();
            } else
            {
                return redirect()->back()->withErrors("User not found for 'from' operation.");
            }
        } else
        {
            $stock = Stock::find($data['from-id']);

            if ($stock->amount < $data['from-amount'])
            {
                return redirect()->back()->withErrors('Insufficient Funds');
            }

            if ($stock)
            {
                $stock->amount -= $data['from-amount'];
                $stock->save();
            } else
            {
                return redirect()->back()->withErrors("Stock not found for 'from' operation.");
            }
        }

        // Handle the 'to' port operation
        if ($data['to-port'] == "user")
        {
            $user = User::find($data['to-id']);
            if ($user)
            {
                $user->amount += $data['to-amount'];
                $user->save();
            } else
            {
                return redirect()->back()->withErrors("User not found for 'to' operation.");
            }
        } else
        {
            $stock = Stock::find($data['to-id']);

            if ($stock)
            {
                $stock->amount += $data['to-amount'];
                $stock->save();
            } else
            {
                return redirect()->back()->withErrors("Stock not found for 'to' operation.");
            }
        }

        // Flash success message and redirect
        session()->flash('message', 'Successfully Liquidated.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back()->with("success", "Successfully Liquidated");
    }

    public function UserTaskSubmit(Request $request)
    {
        dd($request->all());
        return redirect()->back();
    }

    public function getStatement()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Investment Statement";
        $data['member'] = User::findOrFail(Auth::user()->id);
        $mem = User::findOrFail(Auth::user()->id);
        $data['last_deposit'] = Deposit::whereUser_id(Auth::user()->id)->orderBy('id', 'DESC')->take(9)->get();
        $data['total_reference_user'] = User::whereUnder_reference($mem->reference)->count();
        $data['total_deposit'] = Deposit::whereUser_id(Auth::user()->id)->sum('amount');
        /*$data['total_deposit1'] = Deposit::whereUser_id(Auth::user()->id)->sum('amount');
        $data['total_deposit2'] = ManualFund::whereUser_id(Auth::user()->id)->sum('amount');*/
        $data['total_deposit_time'] = Deposit::whereUser_id(Auth::user()->id)->count();
        $data['total_deposit_pending'] = Repeat::whereUser_id(Auth::user()->id)->whereStatus(0)->count();
        $data['total_deposit_complete'] = Repeat::whereUser_id(Auth::user()->id)->whereStatus(1)->count();
        $data['total_rebeat'] = RebeatLog::whereUser_id(Auth::user()->id)->sum('balance');
        $data['total_reference'] = Reference::whereUser_id(Auth::user()->id)->sum('balance');
        $data['total_withdraw_time'] = Withdraw::whereUser_id(Auth::user()->id)->count();
        $data['total_withdraw_pending'] = Withdraw::whereUser_id(Auth::user()->id)->whereStatus(0)->count();
        $data['total_withdraw_complete'] = Withdraw::whereUser_id(Auth::user()->id)->whereStatus(1)->count();
        $data['total_withdraw_refund'] = Withdraw::whereUser_id(Auth::user()->id)->whereStatus(2)->count();
        $data['total_withdraw'] = Withdraw::whereUser_id(Auth::user()->id)->whereStatus(1)->sum('amount');
        $freqid = Auth::user()->id;
        $month = date('m');
        $year = date('Y');

        $cnt = DB::select("select * from statements where user_id = ? AND month = ? AND year = ?", [$freqid, $month, $year]);
        if (!empty($cnt))
        {

            foreach ($cnt as $cn)
            {
                $data['Initial'] = $cn->Added_Fund;
                $data['Opening_Balance'] = $cn->Opening_Balance;
                $data['Less_Payout'] = $cn->Withdrawal;
                $data['Nett_Balance'] = $cn->Net_Balance;
                $data['Growth_Amount'] = $cn->Growth_Amount;
                $data['Percentage_Growth'] = $cn->Percentage_Growth;
                $data['Gross'] = $cn->Gross;
                $data['CommissionAmount'] = $cn->Commission_Amount;
                $data['Closing_Balance'] = $cn->Closing_Balance;
                $data['Available_Payout'] = $cn->Payout;

            }

        }

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);

        return view('user.statement', $data);
    }
    public function addFund()
    {

        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Add Fund";
        $data['payment'] = Payment::first();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.fund-add', $data);
    }
    public function historyFund()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['page_title'] = "User Add Funding History";
        $user_id = Auth::user()->id;
        $data['fund'] = Fund::whereUser_id($user_id)->orderBy('id', 'DESC')->get();
        $data['basic'] = BasicSetting::first();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.fund-history', $data);
    }
    public function newDeposit()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User New Invest";
        $data['payment'] = Payment::first();
        $data['plan'] = Plan::whereStatus(1)->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.deposit-new', $data);
    }
    public function postDeposit(Request $request)
    {


        $this->validate($request, [
            'id' => 'required'
        ]);
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Invest Preview";
        $data['payment'] = Payment::first();
        $data['plan'] = Plan::findOrFail($request->id);

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.deposit-preview', $data);

    }
    public function amountDeposit(Request $request)
    {
        $plan = Plan::findOrFail($request->plan);
        $user = User::findOrFail(Auth::user()->id);
        $amount = $request->amount;

        if ($request->amount > $user->amount)
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Your Current Amount.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button disabled"
                        >
                    <i class="fa fa-send"></i> Deposit Amount Under This Package
                </button>
            </div>';
        }
        if ($plan->minimum > $amount)
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Smaller than Plan Minimum Amount.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button disabled"
                        >
                    <i class="fa fa-send"></i> Deposit Amount Under This Package
                </button>
            </div>';
        } elseif ($plan->maximum < $amount)
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Plan Maximum Amount.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button disabled"
                      >
                    <i class="fa fa-send"></i> Deposit Amount Under This Package
                </button>
            </div>';
        } else
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-success"><i class="fa fa-check"></i> Well Done. Deposit This Amount Under this Package.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button"
                        data-toggle="modal" data-target="#DelModal"
                        data-id=' . $amount . '>
                    <i class="fa fa-send"></i> Deposit Amount Under This Package
                </button>
            </div>';
        }

    }
    public function paypalCheck(Request $request)
    {
        $amount = $request->amount;
        $type = $request->payment_type;
        $basic = Payment::first();
        if ($type == 1)
        {

            if (($amount) < $basic->paypal_min)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Smaller than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } elseif (($amount) > $basic->paypal_max)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } else
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-check"></i> Well Done. Add Fund This Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            }
        } elseif ($type == 2)
        {
            if (($amount) < $basic->perfect_min)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Smaller than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } elseif (($amount) > $basic->perfect_max)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } else
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-check"></i> Well Done. Add Fund This Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            }
        } elseif ($type == 3)
        {
            if (($amount) < $basic->btc_min)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Smaller than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } elseif (($amount) > $basic->btc_max)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } else
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-check"></i> Well Done. Add Fund This Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            }
        } elseif ($type == 4)
        {
            if (($amount) < $basic->stripe_min)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Smaller than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } elseif (($amount) > $basic->stripe_max)
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-times"></i> Amount Is Larger than Funding Minimum Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-info disabled"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            } else
            {
                return '<div class="col-sm-9 col-sm-offset-2" style="margin-bottom: -0.9375rem;">
                    <div class="alert alert-warning"><i class="fa fa-check"></i> Well Done. Add Fund This Amount.</div>
                </div>
                <div class="col-sm-9 col-sm-offset-2" style="text-align: right;margin-top: .625rem;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-send"></i> Add Fund</button>
                </div>';
            }
        }
    }
    public function storeFund(Request $request)
    {

        $this->validate($request, [
            'amount' => 'required',
            'payment_type' => 'required',
            'rate' => 'required'
        ]);
        $fu = Input::except('_method', '_token');
        $fu['transaction_id'] = date('ymd') . Str::random(6) . rand(11, 99);
        $fu['user_id'] = Auth::user()->id;
        $fund = FundLog::create($fu);
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Add Fund Preview";
        $data['payment'] = Payment::first();
        $data['fund'] = $fund;

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.fund-preview', $data);
    }
    public function stripePreview(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['fund_id'] = $request->id;
        $data['charge'] = $request->charge;
        $data['transaction_id'] = $request->transaction;
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Card Preview";
        $data['payment_type'] = 4;
        $data['payment'] = Payment::first();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.stripe-preview', $data);
    }
    public function submitStripe(Request $request)
    {

        $this->validate($request, [
            'amount' => 'required',
            'cardNumber' => 'required',
            'cardExpiryMonth' => 'required',
            'cardExpiryYear' => 'required',
            'cardCVC' => 'required',
        ]);

        $amm = $request->charge;
        $cc = $request->cardNumber;
        $emo = $request->cardExpiryMonth;
        $eyr = $request->cardExpiryYear;
        $cvc = $request->cardCVC;
        $basic = Payment::first();
        Stripe::setApiKey($basic->stripe_secret);
        try
        {
            $token = Token::create(
                array(
                    "card" => array(
                        "number" => "$cc",
                        "exp_month" => $emo,
                        "exp_year" => $eyr,
                        "cvc" => "$cvc"
                    )
                )
            );


            $charge = Charge::create(
                array(
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => round($request->amount, 2) * 100,
                    'description' => 'item',
                )
            );

            if ($charge['status'] == 'succeeded')
            {

                $funlog = FundLog::whereTransaction_id($request->transaction_id)->first();
                $basic = Payment::first();
                $user = User::findOrFail($funlog->user_id);

                $basic = BasicSetting::first();
                // Fun Log
                $fu['user_id'] = $user->id;
                $fu['payment_type'] = 4;
                $fu['transaction_id'] = $funlog->transaction_id;
                $fu['amount'] = $funlog->amount;
                $fu['rate'] = $funlog->rate;
                $fu['charge'] = $amm;
                $fu['total'] = $request->amount;
                Fund::create($fu);

                // user Log
                $us['user_id'] = $user->id;
                $us['balance_type'] = 1;
                $us['details'] = "Fund Add By Credit Card. Transaction #ID " . $funlog->transaction_id;
                $us['balance'] = $funlog->amount;
                $us['charge'] = $amm;
                $us['old_balance'] = $user->amount;
                $us['new_balance'] = $user->amount + $funlog->amount;
                UserBalance::create($us);
                $user->amount = $us['new_balance'];
                $user->save();

                // Admin log
                $ad['user_id'] = $user->id;
                $ad['balance_type'] = 1;
                $ad['details'] = "Fund Deposit By Credit Card. Transaction #ID " . $funlog->transaction_id;
                $ad['balance'] = $funlog->amount;
                $ad['charge'] = $amm;
                $ad['old_balance'] = $basic->admin_total;
                $ad['new_balance'] = $amm + $basic->admin_total + $funlog->amount;
                AdminBalance::create($ad);
                $basic->admin_total = $ad['new_balance'];
                $basic->save();

                session()->flash('message', 'Successfully Card Charged.');
                session()->flash('title', 'Success');
                session()->flash('type', 'success');

                return redirect()->route('add-fund');
            } else
            {
                session()->flash('message', 'Something Is Wrong.');
                session()->flash('title', 'Opps..');
                session()->flash('type', 'warning');
                return redirect()->route('add-fund');
            }

        } catch (Exception $e)
        {
            session()->flash('message', $e->getMessage());
            session()->flash('title', 'Opps..');
            session()->flash('type', 'warning');
            return redirect()->route('add-fund');
        }
    }
    public function btcPreview(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['charge'] = $request->charge;
        $data['transaction_id'] = $request->transaction_id;
        $pay = Payment::first();
        $tran = FundLog::whereTransaction_id($data['transaction_id'])->first();

        $blockchain_root = "https://blockchain.info/";
        $blockchain_receive_root = "https://api.blockchain.info/";
        $mysite_root = url('/');
        $secret = "ABIR";
        $my_xpub = $pay->btc_xpub;
        $my_api_key = $pay->btc_api;

        $invoice_id = $tran->transaction_id;

        $callback_url = route('btc_ipn', ['invoice_id' => $invoice_id, 'secret' => $secret]);



        if ($tran->btc_acc == null)
        {

            $resp = file_get_contents($blockchain_receive_root . "v2/receive?key=" . $my_api_key . '&callback=' . urlencode($callback_url) . '&xpub=' . $my_xpub);

            $response = json_decode($resp);

            $sendto = $response->address;

            if ($sendto != "")
            {
                $api = "https://blockchain.info/tobtc?currency=USD&value=" . $data['amount'];
                $usd = file_get_contents($api);
                $tran->btc_amo = $usd;
                $tran->btc_acc = $sendto;
                $tran->save();
            } else
            {
                session()->flash('message', "SOME ISSUE WITH API");
                Session::flash('type', 'warning');
                return redirect()->back();
            }
        } else
        {
            $usd = $tran->btc_amo;
            $sendto = $tran->btc_acc;
        }
        /*$sendto = "1HoPiJqnHoqwM8NthJu86hhADR5oWN8qG7";
        $usd =100;*/

        /*if ($tran->btc_acc == null){

            if (file_exists($blockchain_receive_root . "v2/receive?key=" . $my_api_key . '&callback=' . urlencode($callback_url) . '&xpub=' . $my_xpub)) {
                $resp = file_get_contents($blockchain_receive_root . "v2/receive?key=" . $my_api_key . '&callback=' . urlencode($callback_url) . '&xpub=' . $my_xpub);

                $response = json_decode($resp);

                $sendto = $response->address;

                $api = "https://blockchain.info/tobtc?currency=USD&value=".$data['amount'];

                $usd = file_get_contents($api);

                $tran->btc_amo = $usd;
                $tran->btc_acc = $sendto;
                $tran->save();
            }else{
                session()->flash('message', 'BlockChain API or XPUB not Correct.');
                Session::flash('type', 'warning');
                Session::flash('title', 'Opps');
                return redirect()->back();
            }
        }else{
            $usd = $tran->btc_amo;
            $sendto = $tran->btc_acc;
        }*/
        /*$sendto = "1HoPiJqnHoqwM8NthJu86hhADR5oWN8qG7";
        $usd =100;*/
        $var = "bitcoin:$sendto?amount=$usd";
        $data['code'] = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$var&choe=UTF-8\" title='' style='width:18.75rem;' />";
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "BTC Send Preview";
        $data['payment_type'] = 3;
        $data['payment'] = Payment::first();
        $data['btc'] = $usd;
        $data['add'] = $sendto;
        $data['fund'] = $tran;

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.btc-send', $data);
    }
    public function depositSubmit(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
            'user_id' => 'required',
            'plan_id' => 'required'
        ]);
        $plan = Plan::findOrFail($request->plan_id);
        $user = User::findOrFail($request->user_id);
        $basic = BasicSetting::first();
        $dep['amount'] = $request->id;
        $dep['percent'] = $plan->percent;
        $dep['time'] = $plan->time;
        $dep['compound_id'] = $plan->compound_id;
        $dep['user_id'] = $user->id;
        $dep['plan_id'] = $plan->id;
        $dep['status'] = 1;
        $dep['deposit_number'] = date('ymd') . Str::random(6) . rand(11, 99);
        $us['user_id'] = $user->id;
        $us['balance_type'] = 2;
        $us['balance'] = $request->id;
        $us['old_balance'] = $user->amount;
        $user->amount = $user->amount - $request->id;
        $us['new_balance'] = $user->amount;
        $user->save();
        $deposit = Deposit::create($dep);
        $us['details'] = "Invest ID: # " . $dep['deposit_number'] . '; ' . "Invest Plan : " . $plan->name;
        UserBalance::create($us);
        $rr['user_id'] = $user->id;
        $rr['deposit_id'] = $deposit->id;
        $rr['repeat_time'] = Carbon::parse()->addHours($plan->compound->compound);
        $refer = Auth::user()->under_reference;
        if ($basic->reference_id == $refer)
        {
            $ref['user_id'] = 0;
            $ref['reference_id'] = $basic->reference_id;
            $ref['under_reference'] = $user->reference;
            $ref['balance'] = ($request->id * $basic->reference) / 100;
            $ref['details'] = "Referral Invest Bonus : " . $ref['balance'] . "; " . $basic->currency . ' Referral ID : # ' . $ref['under_reference'];
            $ref['old_balance'] = $basic->admin_total;
            $ref['new_balance'] = $basic->admin_total;
            Reference::create($ref);

            //admin reference Log
            $ad['user_id'] = 0;
            $ad['balance_type'] = 5;
            $ad['balance'] = $ref['balance'];
            $ad['old_balance'] = $ref['old_balance'];
            $ad['new_balance'] = $ref['old_balance'];
            $ad['details'] = $ref['details'];
            $ad['charge'] = "Default";
            AdminBalance::create($ad);

            //admin balance log

            $ad['user_id'] = Auth::user()->id;
            $ad['balance_type'] = 2;
            $ad['balance'] = $request->id;
            $ad['old_balance'] = $basic->admin_total;
            $ad['new_balance'] = $basic->admin_total + $request->id;
            $ad['details'] = "Invest ID: # " . $dep['deposit_number'] . '; ' . "Invest Plan : " . $plan->name;
            AdminBalance::create($ad);
            $basic->admin_total = $ad['new_balance'];
            $basic->save();

        } else
        {
            /* ---------- Reference Log ---------*/
            $rrrr = User::whereReference(Auth::user()->under_reference)->first();
            $ref['user_id'] = $rrrr->id;
            $ref['reference_id'] = $rrrr->reference;
            $ref['under_reference'] = $user->reference;
            $ref['balance'] = ($request->id * $basic->reference) / 100;
            $ref['details'] = "Referral Invest Bonus : " . $ref['balance'] . "-" . $basic->currency . "; " . ' Referral ID : # ' . $ref['under_reference'];
            $ref['old_balance'] = $rrrr->amount;
            $ref['new_balance'] = $rrrr->amount + $ref['balance'];
            Reference::create($ref);

            /*---- User reference Log ----*/
            $ad1['user_id'] = $rrrr->id;
            $ad1['balance_type'] = 5;
            $ad1['balance'] = $ref['balance'];
            $ad1['old_balance'] = $rrrr->amount;
            $ad1['new_balance'] = $rrrr->amount + $ad1['balance'];
            $ad1['details'] = $ref['details'];
            UserBalance::create($ad1);

            $rrrr->amount = $ref['new_balance'];
            $rrrr->save();

            /* ------ Admin reference Log -------*/
            $ad['user_id'] = $rrrr->id;
            $ad['balance_type'] = 5;
            $ad['balance'] = $ref['balance'];
            $ad['old_balance'] = $basic->admin_total;
            $ad['new_balance'] = $basic->admin_total - $ad['balance'];
            $ad['details'] = $ref['details'];
            AdminBalance::create($ad);
            $basic->admin_total = $ad['new_balance'];
            $basic->save();

            $ad1['user_id'] = Auth::user()->id;
            $ad1['balance_type'] = 2;
            $ad1['balance'] = $request->id;
            $ad1['old_balance'] = $basic->admin_total;
            $ad1['new_balance'] = $basic->admin_total + $request->id;
            $ad1['details'] = "Invest ID: # " . $dep['deposit_number'] . '; ' . "Invest Plan : " . $plan->name;
            AdminBalance::create($ad1);
            $basic->admin_total = $ad1['new_balance'];
            $basic->save();
        }

        session()->flash('message', 'Deposit Completed Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->route('manual-payment-request');
        /*return redirect()->back();*/
    }
    public function depositHistory()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Deposit History";
        $data['deposit'] = Deposit::whereUser_id(Auth::user()->id)->orderBy('id', 'DESC')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.deposit-history', $data);
    }
    public function repeatHistory()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Profit History";
        $data['deposit'] = Deposit::whereUser_id(Auth::user()->id)->orderBy('id', 'DESC')->paginate(9);

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.repeat-history', $data);
    }
    public function repeatTable($id)
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Profit Table";
        $data['repeat'] = RebeatLog::whereDeposit_id($id)->whereUser_id(Auth::user()->id)->orderBy('id', 'ASC')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.repeat-table', $data);
    }
    public function referenceUser()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Reference User";
        $data['user'] = User::whereUnder_reference(Auth::user()->reference)->orderBy('id', 'desc')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.reference-user', $data);
    }
    public function referenceHistory()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Reference Bonus History";
        $data['bonus'] = Reference::whereUser_id(Auth::user()->id)->orderBy('id', 'desc')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.reference-history', $data);
    }


    public function addProfile(Request $request)
    {
        /*dd($request);*/
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'ID_Number' => 'required',
            'image' => 'mimes:jpg,png,jpeg',
        ]);
        $us = Input::except('_method', '_token', 'email');
        $password = Hash::make('123456');
        $us->password = $password;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(450, 600)->save($location);
            $us['image'] = $filename;
        }
        //$user = User::findOrFail($id);
        $us->save();
        session()->flash('message', 'User Added Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->route('user-dashboard');

    }

    public function userActivity()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User All Activity";
        $data['activity'] = UserBalance::whereUser_id(Auth::user()->id)->orderBy('id', 'desc')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.user-activity', $data);
    }
    public function editUser()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Details Update ";
        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);

        return view('user.user-edit', $data);
    }
    public function updateUser(Request $request, $id)
    {

        /*dd($request);*/
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'ID_Number' => 'required',
            'image' => 'mimes:jpg,png,jpeg',
        ]);
        $us = Input::except('_method', '_token', 'email');
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(450, 600)->save($location);
            $us['image'] = $filename;
        }
        $user = User::findOrFail($id);
        $user->fill($us)->save();
        session()->flash('message', 'User Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->route('user-dashboard');
    }
    public function userPassword()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Password Update ";
        $data['page_title'] = "User Password Updat ";
        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);

        return view('user.user-password', $data);
    }
    public function updatePassword(Request $request, $id)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        try
        {
            $c_password = Auth::user()->password;
            $user = User::findOrFail($id);

            if (Hash::check($request->current_password, $c_password))
            {

                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                session()->flash('message', 'Password Changes Successfully.');
                Session::flash('type', 'success');
                Session::flash('title', 'Success');
                return redirect()->back();
            } else
            {
                session()->flash('message', 'Password Not Match');
                Session::flash('type', 'warning');
                Session::flash('title', 'Opps..!');
                return redirect()->back();
            }

        } catch (\PDOException $e)
        {
            session()->flash('message', 'Some Problem Occurs, Please Try Again!');
            Session::flash('type', 'warning');
            return redirect()->back();
        }
    }
    public function autoDeposit(Request $request)
    {
        $amount = $request->amount;
        $plan_id = $request->plan_id;
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "User Deposit Preview";
        $data['payment'] = Payment::first();
        $data['plan'] = Plan::findOrFail($plan_id);
        $data['amount'] = $amount;
        $data['hit'] = (Auth::user()->amount < $amount) ? 1 : 0;

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('user.deposit-auto-preview', $data);
    }

    public function manualFundAdd()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Fund Add via Bank";
        $data['bank'] = ManualBank::whereStatus(1)->get();
        $data['crypto'] = ManualCrypto::whereStatus(1)->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('bank.manual-fund', $data);
    }

    public function fundAddCheck(Request $request)
    {

        $amount = $request->amount;
        $method = $request->method_id;
        $bank = ManualBank::findOrFail($method);

        if ($request->amount < $bank->minimum or $request->amount > $bank->maximum)
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-warning"><i class="fa fa-times"></i> You can not add this Amount</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button disabled"
                        >
                    <i class="fa fa-send"></i> Add Fund
                </button>
            </div>';
        } else
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-success"><i class="fa fa-check"></i> Well Done. You Can add This Deposit.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="submit" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button"
                        data-toggle="modal" data-target="#DelModal"
                        data-id=' . $amount . '>
                    <i class="fa fa-send"></i> Add Fund
                </button>
            </div>';
        }
    }

    public function fundAddCheckCrypto(Request $request)
    {

        $amount = $request->amount - 5000;
        $method = $request->method_id - 5000;
        $bank = ManualCrypto::findOrFail($method);

        if ($request->amount < $bank->minimum or $request->amount > $bank->maximum)
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-warning"><i class="fa fa-times"></i> You can not add this Amount</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="button" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button disabled"
                        >
                    <i class="fa fa-send"></i> Add Fund
                </button>
            </div>';
        } else
        {
            return '<div class="col-sm-7 col-sm-offset-4">
                <div class="alert alert-success"><i class="fa fa-check"></i> Well Done. You Can add This Deposit.</div>
            </div>
            <div class="col-sm-7 col-sm-offset-4">
                <button type="submit" class="btn btn-info btn-block btn-icon btn-lg icon-left delete_button"
                        data-toggle="modal" data-target="#DelModal"
                        data-id=' . $amount . '>
                    <i class="fa fa-send"></i> Add Fund
                </button>
            </div>';
        }
    }
    public function StoreManualFundAdd(Request $request)
    {
        $mu['amount'] = $request->amount;
        $mu['trans_id'] = $request->method_id;
        $mu['type'] = "bank";
        $mu['user_id'] = Auth::user()->id;
        $mu['transaction_id'] = date('ymd') . Str::random(6) . rand(11, 99);


        // Fetch the manual bank or crypto based on the method_id
        if ($request->method_id >= 5000)
        {
            $crypto = ManualCrypto::findOrFail($request->method_id - 5000);
            $mu['trans_id'] = $request->method_id - 5000;
            $mu['type'] = "crypto";
            $mu['charge'] = $crypto->fix + (($request->amount * $crypto->percent) / 100);
            $mu['total'] = $request->amount + $mu['charge'];

        } else
        {
            $bank = ManualBank::findOrFail($request->method_id);
            $mu['charge'] = $bank->fix + (($request->amount * $bank->percent) / 100);
            $mu['total'] = $request->amount + $mu['charge'];
        }

        // Create ManualFundLog record
        $data['fund'] = ManualFundLog::create($mu);

        // Prepare data for the view
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Bank Deposits Preview";
        $data['member'] = User::findOrFail(Auth::user()->id);
        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['member']->ID_Number]);

        // Determine type and method based on bank_id
        if ($request->method_id >= 5000)
        {
            $data['type'] = 'crypto';
            $data['method'] = $crypto;
            $data['hold'] = $request->holder;
        } else
        {
            $data['type'] = 'bank';
            $data['method'] = $bank;
            $data['hold'] = null;
        }

        return view('bank.manual-fund-preview', $data);
    }

    public function submitManualFund(Request $request)
    {
        $mu['manual_fund_log_id'] = $request->log_id;
        $mu['message'] = $request->message;
        $am = ManualFundLog::findOrFail($request->log_id);
        $mu['amount'] = $am->total;
        $mu['user_id'] = Auth::user()->id;
        $ad = ManualFund::create($mu);
        if ($request->hasFile('image'))
        {
            $image3 = $request->file('image');
            foreach ($image3 as $i)
            {
                $filename3 = time() . uniqid() . '.' . $i->getClientOriginalExtension();
                $location = 'assets/upload/' . $filename3;
                Image::make($i)->save($location);
                $image['image'] = $filename3;
                $image['fund_id'] = $ad->id;
                Photo::create($image);
            }
        }
        session()->flash('message', 'Request Successfully Completed.');
        Session::flash('title', 'Success');
        Session::flash('type', 'success');
        return redirect()->back();

    }
    public function manualFundHistory()
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = " Fund Add History";
        $data['fund'] = ManualFund::whereUser_id(Auth::user()->id)->orderBy('id', 'desc')->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('bank.manual-fund-history', $data);
    }
    public function manualFundAddDetails($id)
    {
        $data['general'] = GeneralSetting::first();
        $data['site_title'] = $data['general']->title;
        $data['basic'] = BasicSetting::first();
        $data['page_title'] = "Bank Payment Request";
        $data['fund'] = ManualFund::findOrFail($id);
        $data['img'] = Photo::whereFund_id($id)->get();

        $data['member'] = User::findOrFail(Auth::user()->id);

        $data['namew'] = $data['member']->ID_Number;
        $data['withdrawalcnt'] = '';

        $data['withdrawalcnt'] = DB::select("select * from users where ID_Number = ?", [$data['namew']]);
        return view('bank.manual-payment-request-view', $data);
    }





    // Inside User Controller
    public function user_switch_start($new_user)
    {
        $new_user = User::find($new_user);
        Session::put('orig_user', Auth::id());
        Auth::login($new_user);
        return redirect()->back();
    }

    public function user_switch_stop()
    {
        $id = Session::pull('orig_user');
        $orig_user = User::find($id);
        Auth::login($orig_user);
        return redirect()->back();
    }
}
