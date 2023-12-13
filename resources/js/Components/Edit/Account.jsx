import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default function Account({ account, apiToken }) {
    console.log(apiToken);
    if (account === null) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No Account Found For this User
                </div>
            </div>
        );
    }

    const [modalMessage, setModalMessage] = useState("");

    const [formData, setFormData] = useState({
        user_id: account.user_id,
        balance: account.balance,
        bonus: account.bonus,
        trade: account.trade,
        earning: account.earning,
        account_type: account.account_type,
        account_stage: account.account_stage,
        // Add other user fields as needed
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        axios
            .put(`/api/account/${account.id}`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer 1|${apiToken}`,
                },
            })
            .then((res) => {
                console.log(res);

                setModalMessage("Account was updated successfully");
                // Redirect to account details page after successful update
                Inertia.visit(`/dashboard/${account.user_id}`);

                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            })
            .catch((error) => {
                setModalMessage(error.response.data.message);
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            });
    };

    const handleToggle = () => {
        setFormData((prevFormData) => ({
            ...prevFormData,
            trade: prevFormData.trade === 0 ? 1 : 0,
        }));
    };

    const formattedDate = new Date(account.updated_at).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    });

    return (
        <div className="container mx-auto mt-8">
            <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                <div>
                    <h2 className="text-2xl font-semibold mb-4">
                        Edit Account
                    </h2>
                    <p className="text-sm text-gray-600">{formattedDate}</p>
                </div>

                <form onSubmit={handleSubmit}>
                    
                    <div className="flex items-center">
                        <span className="mr-2">Trade:</span>
                        <label className="switch">
                            <input
                                type="checkbox"
                                checked={formData.trade === 1}
                                onChange={() => handleToggle()} // Add your toggle handler function
                                className="hidden"
                            />
                            <span className="slider round"></span>
                        </label>{" "}
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="balance"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Balance
                        </label>
                        <input
                            type="text"
                            id="balance"
                            name="balance"
                            defaultValue={formData.balance}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="bonus"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Bonus
                        </label>
                        <input
                            type="text"
                            id="bonus"
                            name="bonus"
                            value={formData.bonus}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="earning"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Earning
                        </label>
                        <input
                            type="text"
                            id="earning"
                            name="earning"
                            value={formData.earning}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="account_type"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Account Type
                        </label>
                        <select
                            id="account_type"
                            name="account_type"
                            defaultValue={formData.account_type}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        >
                            <option value="trading">Trading</option>
                            <option value="margin">Margin</option>
                        </select>
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="account_stage"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Account Stage
                        </label>
                        <select
                            id="account_stage"
                            name="account_stage"
                            defaultValue={formData.account_stage}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        >
                            <option value="bronze">bronze</option>
                            <option value="silver">Silver</option>
                            <option value="gold">Gold</option>
                            <option value="premium">premium</option>
                        </select>
                    </div>

                    {/* Add other user fields as needed */}

                    <div className="mt-4">
                        <button
                            type="submit"
                            className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                        >
                            Update Account
                        </button>
                    </div>
                </form>
            </div>
            {modalMessage && (
                <div
                    className={`fixed bottom-0 right-0 p-4 ${
                        modalMessage.includes("successfully")
                            ? "bg-green-500"
                            : "bg-red-500"
                    } text-white`}
                >
                    {modalMessage}
                </div>
            )}
        </div>
    );
}