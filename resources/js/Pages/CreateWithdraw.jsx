import React, { useState, useEffect, useRef } from "react";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default function CreateWithdraw({ user_id }) {
    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");
    const [formData, setFormData] = useState({
        user_id: user_id,
        amount: "",
        currency: "",
        status: 0,
        destination: "",
        name: "",
        withdrawal_type: "bank_transfer",
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
            .post(`/api/withdraw`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                console.log(res);
                // setModalMessage("withdraw was created successfully");
                // // Redirect to withdraw details page after successful update

                // form.current.reset();
                // setFormData({
                //     user_id: user_id,
                //     amount: "",
                //     currency: "",
                //     status: 0,
                //     destination: "",
                //     name: "",
                //     withdrawal_type: "bank_transfer",
                // });

                // setTimeout(() => {
                //     setModalMessage("");
                // }, 2000);
            })
            .catch((error) => {
                console.log(error);
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
                    <h2 className="text-2xl font-semibold">Create Withdraw</h2>
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
                            placeholder="100"
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
                            Holder
                        </label>
                        <input
                            type="text"
                            id="currency"
                            placeholder="CryptoCurrency Name OR Bank Name"
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
                            placeholder="3140938909 OR Wallet address"
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
                            placeholder="John Smith"
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
                        <select
                            type="text"
                            id="withdrawal_type"
                            name="withdrawal_type"
                            value={formData.withdrawal_type}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        >
                            <option defaultValue value="bank_transfer">
                                Bank Transfer
                            </option>
                            <option value="crypto">Crypto</option>
                        </select>
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
