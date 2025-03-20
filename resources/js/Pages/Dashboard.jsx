import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ListUsers from "@/Components/ListUsers";
import { InertiaLink } from "@inertiajs/inertia-react";
import Pagination from "@/Components/Pagination";
export default function Dashboard({ auth, users }) {
    return (
        <div>
            <AuthenticatedLayout
                user={auth.user}
                header={
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard
                    </h2>
                }
            >
                <Head title="Dashboard" />
                <div className="flex flex-wrap">
                    <button className=" m-4  px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink href={`/dashboard/plan/edit`} method="get">
                            Edit Plans Here
                        </InertiaLink>
                    </button>

                    <button className=" m-4  px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink
                            href={`/dashboard/withdraw_type/edit`}
                            method="get"
                        >
                            Edit Withdraw Type
                        </InertiaLink>
                    </button>

                    {/* <button className=" m-4  px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink
                            href={`/dashboard/wallet/edit`}
                            method="get"
                        >
                            Edit Wallet Option
                        </InertiaLink>
                    </button> */}

                    <button className=" m-4  px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink
                            href={`/dashboard/deposit_type/edit`}
                            method="get"
                        >
                            Edit Deposit Option
                        </InertiaLink>
                    </button>

                    <button className=" m-4  px-4 py-2 bg-green-500 text-white rounded hover:bg-yellow-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink
                            href={`/dashboard/account_type/edit`}
                            method="get"
                        >
                            Edit Account Type
                        </InertiaLink>
                    </button>

                    {/* <button className=" m-4  px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 focus:outline-none focus:ring focus:border-blue-300">
                        <InertiaLink
                            href={`/dashboard/trader/edit`}
                            method="get"
                        >
                            Edit Traders
                        </InertiaLink>
                    </button> */}
                </div>

                <ListUsers users={users.data} />
                <Pagination data={users} />
            </AuthenticatedLayout>
        </div>
    );
}
