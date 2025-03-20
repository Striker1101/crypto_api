import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { InertiaLink } from "@inertiajs/inertia-react";
import { Link } from "@inertiajs/react";
import axios from "axios";
import { getToken } from "@/Util/transform";

export default function Withdraw({ withdraw, user_id }) {
    if (withdraw === null) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No withdraw Found For this User
                </div>
            </div>
        );
    }

    const token = getToken();
    const [reload, setreload] = useState(true);
    const [modalMessage, setModalMessage] = useState("");

    const handleToggle = (id, e) => {
        const selectedStatus = e.target.value;
        if (!selectedStatus) return;

        const updatedWithdraw = withdraw.map((item) => {
            if (item.id === id) {
                const updatedItem = { ...item, status: selectedStatus };
                handleSubmit(updatedItem); // send updated item
                return updatedItem;
            }
            return item;
        });

        // Optional: update state if you're managing deposit in state
        // setDeposit(updatedDeposit);
        setreload(!reload);
    };

    const handleSubmit = (formData) => {
        axios
            .put(`/api/withdraw/${formData.id}`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setModalMessage("withdraw was updated successfully");
                // Redirect to withdraw details page after successful update
                Inertia.visit(`/dashboard/${formData.user_id}`);

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

    const handleDelete = (id) => {
        if (confirm("Are you sure you want to delete this withdraw"))
            axios
                .delete(`/api/withdraw/${id}`, {
                    headers: {
                        "Content-Type": "application/json",
                        // Add any other headers if needed
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((res) => {
                    console.log(res);

                    setModalMessage("withdraw was deleted successfully");
                    // Redirect to withdraw details page after successful update
                    Inertia.visit(`/dashboard/${formData.user_id}`);

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
            <div className="max-w-xxl mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-2xl font-semibold">Edit withdraw</h2>
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/withdraw`}
                        method="get"
                        as="button"
                    >
                        Add withdraw
                    </Link>
                </div>
                <table className="min-w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                    <thead className="bg-gray-100">
                        <tr className="text-left text-sm font-semibold text-gray-600">
                            <th className="px-4 py-3">Time</th>
                            <th className="px-4 py-3">Name</th>
                            <th className="px-4 py-3">Type</th>
                            <th className="px-4 py-3">Amount</th>
                            <th className="px-4 py-3">Currency</th>
                            <th className="px-4 py-3">Destination</th>
                            <th className="px-4 py-3">Account</th>
                            <th className="px-4 py-3">Status</th>
                            <th className="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {withdraw?.map((item, idx) => (
                            <tr
                                key={item.id}
                                className={`text-sm text-gray-700 border-t ${
                                    idx % 2 === 0 ? "bg-white" : "bg-gray-50"
                                } hover:bg-gray-100 transition duration-150`}
                            >
                                <td className="px-4 py-3">
                                    {new Date(item.updated_at).toLocaleString(
                                        "en-US",
                                        {
                                            year: "numeric",
                                            month: "short",
                                            day: "numeric",
                                            hour: "numeric",
                                            minute: "numeric",
                                            hour12: true,
                                        }
                                    )}
                                </td>
                                <td className="px-4 py-3">
                                    {item.withdrawal_type.name}
                                </td>
                                <td className="px-4 py-3">
                                    {item.withdrawal_type.type}
                                </td>
                                <td className="px-4 py-3 font-semibold text-gray-800">
                                    {item.amount}
                                </td>
                                <td className="px-4 py-3">
                                    {item.withdrawal_type.currency}
                                </td>
                                <td className="px-4 py-3">
                                    {item.details.bank_name ||
                                        item.details.network ||
                                        item.details.paypal_email}
                                </td>

                                <td className="px-4 py-3">
                                    {item.details.account_number ||
                                        item.details.wallet_address ||
                                        item.details.paypal_email}
                                </td>

                                <td className="px-4 py-3">
                                    <select
                                        name="status"
                                        className="w-full border-gray-300 rounded-md text-sm px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value={item.status}
                                        onChange={(e) =>
                                            handleToggle(item.id, e)
                                        }
                                    >
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="completed">
                                            Completed
                                        </option>
                                        <option value="rejected">
                                            Rejected
                                        </option>
                                        <option value="processing">
                                            Processing
                                        </option>
                                    </select>
                                </td>
                                <td className="px-4 py-3">
                                    <button
                                        onClick={() => handleDelete(item.id)}
                                        className="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1.5 rounded-md shadow-sm transition"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
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
