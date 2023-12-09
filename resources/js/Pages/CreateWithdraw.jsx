import React, { useState, useEffect, useRef } from "react";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default function CreateWithdraw({ user_id }) {
    const [modalMessage, setModalMessage] = useState("");

    const [formData, setFormData] = useState({
        user_id: user_id,
        amount: "",
        currency: "",
        status: 0,
        destination: "",
        name: "",
        withdrawal_type: "",
        // Add other user fields as needed
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    //holds form element
    const form = useRef();

    const handleSubmit = (e) => {
        e.preventDefault();

        axios
            .post(`/api/storeWithdraw`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization:
                        "Bearer 1|Pgtgzj7z3KFINtsff5sXjebfNe20Putf2Wv3GmkNcb8264a3",
                },
            })
            .then((res) => {
                setModalMessage("withdraw was created successfully");
                // Redirect to withdraw details page after successful update

                form.current.reset();
                setFormData({
                    user_id: user_id,
                    amount: "",
                    currency: "",
                    status: 0,
                    destination: "",
                    name: "",
                    withdrawal_type: "",
                });

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

    return (
        <div className="container mx-auto mt-8">
            <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-2xl font-semibold">Edit Withdraw</h2>
                    <button
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        onClick={() => window.history.back()}
                    >
                        BACK
                    </button>
                </div>

                <form ref={form} onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label
                            htmlFor="user_id"
                            className="block text-sm font-medium text-gray-600"
                        >
                            User ID
                        </label>
                        <input
                            type="number"
                            id="user_id"
                            name="user_id"
                            disabled
                            defaultValue={formData.user_id}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="amount"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Amount
                        </label>
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            defaultValue={formData.amount}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="currency"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Currency
                        </label>
                        <input
                            type="text"
                            id="currency"
                            name="currency"
                            value={formData.currency}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="destination"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Destination
                        </label>
                        <input
                            type="text"
                            id="destination"
                            name="destination"
                            value={formData.destination}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="name"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value={formData.name}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="withdrawal_type"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Withdrawal Type
                        </label>
                        <input
                            type="text"
                            id="withdrawal_type"
                            name="withdrawal_type"
                            value={formData.withdrawal_type}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    {/* Add other user fields as needed */}

                    <div className="mt-4">
                        <button
                            type="submit"
                            className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                        >
                            Update withdraw
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
