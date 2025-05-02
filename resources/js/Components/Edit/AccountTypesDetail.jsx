import React, { useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { getToken } from "@/Util/transform";
const token = getToken();
export default function AccountTypesDetail({
    accountTypes,
    formData,
    setFormData,
}) {
    function getDate(update_at) {
        return new Date(update_at).toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true,
        });
    }

    function handleDelete(index) {
        // Delete on server
        axios
            .delete(`/api/account_type/${index}`, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`, // Ensure token is defined
                },
            })
            .then((res) => {
                // Remove the deleted item from frontend state
                setFormData((prevData) =>
                    prevData.filter((item) => item.id !== index)
                );
            })
            .catch((err) => {
                console.error("Error deleting withdraw_type:", err);
            });
    }

    return (
        <div id="container">
            {formData.map((element, index) => {
                return (
                    <div className="container mx-auto mt-8" key={index}>
                        <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                            <div className="flex justify-between">
                                <div>
                                    <h2 className="text-2xl font-semibold mb-4">
                                        {`Edit ${element.name}`}
                                    </h2>
                                    <p className="text-sm text-gray-600">
                                        {getDate(element.updated_at)}
                                    </p>
                                </div>

                                <button
                                    onClick={() => {
                                        handleDelete(element.id);
                                    }}
                                    className="bg-red-500 h-10 text-white py-2 px-4 rounded-md hover:bg-red-600"
                                >
                                    delete
                                </button>
                            </div>

                            <AccountTypeForm
                                element={element}
                                index={index}
                                formData={formData}
                                setFormData={setFormData}
                            />
                        </div>
                    </div>
                );
            })}
        </div>
    );
}

export function AccountTypeForm({
    element,
    index,
    isAdd = false,
    setIsModalOpen,
    setFormData,
    formData,
}) {
    const [addForm, setAddForm] = useState({
        name: "",
        leverage: "",
        stop_out: "",
        amount: 0,
        spreads: 0,
        minimum_trade_volume: 0,
        scalping: false,
        negative_balance_protection: false,
        swap_free: false,
        hedging_allowed: false,
        daily_signals: false,
        video_tutorials: false,
        dedicated_account_manager: false,
        daily_market_review: false,
        financial_plan: false,
        risk_management_plan: false,
    });

    const handleAddChange = (e) => {
        const { name, type, checked, value } = e.target;
        setAddForm((prev) => ({
            ...prev,
            [name]: type === "checkbox" ? checked : value,
        }));
    };

    const handleChange = (e, index) => {
        const { name, type, checked, value } = e.target;
        setFormData((prevFormData) => {
            const updatedFormData = [...prevFormData];
            updatedFormData[index] = {
                ...updatedFormData[index],
                [name]: type === "checkbox" ? checked : value,
            };
            console.log(updatedFormData); // Log updated state
            return updatedFormData;
        });
    };

    const handleSubmit = (i, id) => {
        console.log(formData, i);
        axios
            .put(`/api/account_type/${id}`, formData[i], {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                toast.success(`${formData[i].name} was updated successfully`);
            })
            .catch((error) => {
                // console.log(error);
                toast.error(error.response.data.message);
            });
    };

    function formReset() {
        setAddForm({
            name: "",
            leverage: "",
            stop_out: "",
            amount: 0,
            spreads: 0,
            minimum_trade_volume: 0,
            scalping: false,
            negative_balance_protection: false,
            swap_free: false,
            hedging_allowed: false,
            daily_signals: false,
            video_tutorials: false,
            dedicated_account_manager: false,
            daily_market_review: false,
            financial_plan: false,
            risk_management_plan: false,
        });
    }

    const handleAddSubmit = () => {
        const data = {
            name: addForm.name,
            leverage: addForm.leverage,
            stop_out: addForm.stop_out,
            scalping: addForm.scalping,
            negative_balance_protection: addForm.negative_balance_protection,
            swap_free: addForm.swap_free,
            hedging_allowed: addForm.hedging_allowed,
            daily_signals: addForm.daily_signals,
            video_tutorials: addForm.video_tutorials,
            dedicated_account_manager: addForm.dedicated_account_manager,
            daily_market_review: addForm.daily_market_review,
            financial_plan: addForm.financial_plan,
            risk_management_plan: addForm.risk_management_plan,
            amount: parseInt(addForm.amount),
            spreads: parseInt(addForm.spreads),
            minimum_trade_volume: parseInt(addForm.minimum_trade_volume),
        };
        axios
            .post(`/api/account_type`, data, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                toast.success(`${addForm.name} was successfully created`);
                formReset();
                setIsModalOpen(false);
            })
            .catch((error) => {
                // console.log(error);
                toast.error(error?.response?.data?.message);
            });
    };

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                isAdd ? handleAddSubmit() : handleSubmit(index, element?.id);
            }}
        >
            {/* name */}
            <div className="mb-4">
                <label
                    htmlFor="balance"
                    className="block text-sm font-medium text-gray-600"
                >
                    Name
                </label>
                <input
                    type="text"
                    id={isAdd ? "name" : element?.name}
                    name="name"
                    defaultValue={isAdd ? addForm.name : element?.name}
                    placeholder="Enter Name"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* leverage */}
            <div className="mb-4">
                <label
                    htmlFor="leverage"
                    className="block text-sm font-medium text-gray-600"
                >
                    Leverage
                </label>
                <input
                    type="text"
                    id={isAdd ? "leverage" : element?.leverage}
                    name="leverage"
                    defaultValue={isAdd ? addForm.leverage : element?.leverage}
                    placeholder="Enter Leverage"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* stop_out  */}
            <div className="mb-4">
                <label
                    htmlFor="stop_out"
                    className="block text-sm font-medium text-gray-600"
                >
                    Stop Out
                </label>
                <input
                    type="text"
                    id={isAdd ? "stop_out" : element?.stop_out}
                    name="stop_out"
                    defaultValue={isAdd ? addForm.stop_out : element?.stop_out}
                    placeholder="Enter  Stop Out"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* spreads */}
            <div className="mb-4">
                <label
                    htmlFor="spreads"
                    className="block text-sm font-medium text-gray-600"
                >
                    Spreads
                </label>
                <input
                    type="number"
                    id={isAdd ? "spreads" : element?.spreads}
                    name="currency"
                    placeholder="Enter Spreads"
                    defaultValue={isAdd ? addForm.spreads : element?.spreads}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* amount */}
            <div className="mb-4">
                <label
                    htmlFor="amount"
                    className="block text-sm font-medium text-gray-600"
                >
                    Amount
                </label>
                <input
                    type="number"
                    id={isAdd ? "amount" : element?.amount}
                    name="amount"
                    placeholder="Enter Amount"
                    defaultValue={isAdd ? addForm.amount : element?.amount}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* minimum_trade_volume */}
            <div className="mb-4">
                <label
                    htmlFor="minimum_trade_volume"
                    className="block text-sm font-medium text-gray-600"
                >
                    Minimum Trade Volume
                </label>
                <input
                    type="number"
                    id={
                        isAdd
                            ? "minimum_trade_volume"
                            : element?.minimum_trade_volume
                    }
                    name="minimum_trade_volume"
                    placeholder="Enter Minimum Trade Volume"
                    defaultValue={
                        isAdd
                            ? addForm.minimum_trade_volume
                            : element?.minimum_trade_volume
                    }
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>
            {/* scalping */}
            <div className="mb-4">
                <label
                    htmlFor={`scalping-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Scalping
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`scalping-${index}`}
                        name="scalping"
                        checked={
                            isAdd
                                ? addForm.scalping == 1
                                    ? true
                                    : false
                                : element?.scalping == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* negative_balance_protection */}
            <div className="mb-4">
                <label
                    htmlFor={`negative_balance_protection-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Negative Balance Protection
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`negative_balance_protection-${index}`}
                        name="negative_balance_protection"
                        checked={
                            isAdd
                                ? addForm.negative_balance_protection == 1
                                    ? true
                                    : false
                                : element?.negative_balance_protection == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* swap_free */}
            <div className="mb-4">
                <label
                    htmlFor={`swap_free-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Negative Balance Protection
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`swap_free-${index}`}
                        name="swap_free"
                        checked={
                            isAdd
                                ? addForm.swap_free == 1
                                    ? true
                                    : false
                                : element?.swap_free == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* hedging_allowed */}
            <div className="mb-4">
                <label
                    htmlFor={`hedging_allowed-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Hedging Allowed
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`hedging_allowed-${index}`}
                        name="hedging_allowed"
                        checked={
                            isAdd
                                ? addForm.hedging_allowed == 1
                                    ? true
                                    : false
                                : element?.hedging_allowed == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* daily_signals */}
            <div className="mb-4">
                <label
                    htmlFor={`daily_signals-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Daily Signals
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`daily_signals-${index}`}
                        name="daily_signals"
                        checked={
                            isAdd
                                ? addForm.daily_signals == 1
                                    ? true
                                    : false
                                : element?.daily_signals == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* video_tutorials */}
            <div className="mb-4">
                <label
                    htmlFor={`video_tutorials-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Video Tutorials
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`video_tutorials-${index}`}
                        name="video_tutorials"
                        checked={
                            isAdd
                                ? addForm.video_tutorials == 1
                                    ? true
                                    : false
                                : element?.video_tutorials == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* dedicated_account_manager */}
            <div className="mb-4">
                <label
                    htmlFor={`dedicated_account_manager-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Dedicated Account Manager
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`dedicated_account_manager-${index}`}
                        name="dedicated_account_manager"
                        checked={
                            isAdd
                                ? addForm.dedicated_account_manager == 1
                                    ? true
                                    : false
                                : element?.dedicated_account_manager == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* daily_market_review */}
            <div className="mb-4">
                <label
                    htmlFor={`daily_market_review-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Daily Market Review
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`daily_market_review-${index}`}
                        name="daily_market_review"
                        checked={
                            isAdd
                                ? addForm.daily_market_review == 1
                                    ? true
                                    : false
                                : element?.daily_market_review == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* financial_plan */}
            <div className="mb-4">
                <label
                    htmlFor={`financial_plan-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Financial Plan
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`financial_plan-${index}`}
                        name="financial_plan"
                        checked={
                            isAdd
                                ? addForm.financial_plan == 1
                                    ? true
                                    : false
                                : element?.financial_plan == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* risk_management_plan */}
            <div className="mb-4">
                <label
                    htmlFor={`risk_management_plan-${index}`}
                    className="block text-sm font-medium text-gray-600 mb-1"
                >
                    Risk Management Plan
                </label>
                <label className="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        id={`risk_management_plan-${index}`}
                        name="risk_management_plan"
                        checked={
                            isAdd
                                ? addForm.risk_management_plan == 1
                                    ? true
                                    : false
                                : element?.risk_management_plan == 1
                                ? true
                                : false
                        }
                        onChange={(e) =>
                            isAdd ? handleAddChange(e) : handleChange(e, index)
                        }
                        className="sr-only peer"
                    />
                    <div className="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                </label>
            </div>

            {/* Add other user fields as needed */}
            <div className="mt-4">
                <button
                    type="submit"
                    className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                >
                    {isAdd ? "Add Account Type" : `Update ${element?.name}`}
                </button>
            </div>
        </form>
    );
}
