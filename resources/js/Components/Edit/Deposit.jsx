import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/react";
import axios from "axios";

export default function Deposit({ deposit, user_id, apiToken }) {
    if (deposit === null) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No Deposit Found For this User
                </div>
            </div>
        );
    }
    const [reload, setreload] = useState(true);
    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");
    const handleToggle = (id, e) => {
        const selectedStatus = e.target.value;
        if (!selectedStatus) return;
        const updatedDeposit = deposit.map((item) => {
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
            .put(`/api/deposit/${formData.id}`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setModalMessage("deposit was updated successfully");
                // Redirect to deposit details page after successful update
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
        if (confirm("Are you sure you want to delete this deposit"))
            axios
                .delete(`/api/deposit/${id}`, {
                    headers: {
                        "Content-Type": "application/json",
                        // Add any other headers if needed
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((res) => {
                    console.log(res);

                    setModalMessage("deposit was deleted successfully");
                    // Redirect to deposit details page after successful update
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
                    <h2 className="text-2xl font-semibold">Edit Deposit</h2>
                    {/* <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/deposit`}
                        method="get"
                        as="button"
                    >
                        Add Deposit
                    </Link> */}
                </div>
                <table className="min-w-full table-auto border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <thead className="bg-gray-100 text-gray-700 text-sm uppercase">
                        <tr>
                            <th className="px-4 py-3 text-left">Time</th>
                            <th className="px-4 py-3 text-left">Amount</th>
                            <th className="px-4 py-3 text-left">Currency</th>
                            <th className="px-4 py-3 text-left">Type</th>
                            <th className="px-4 py-3 text-left">Name</th>
                            <th className="px-4 py-3 text-left">Status</th>
                            <th className="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="text-sm text-gray-700">
                        {deposit?.map((item) => (
                            <tr
                                key={item.id}
                                className="hover:bg-gray-50 border-b"
                            >
                                <td className="px-4 py-2">
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
                                <td className="px-4 py-2 font-semibold">
                                    {item.amount}
                                </td>
                                <td className="px-4 py-2">{item.currency}</td>
                                <td className="px-4 py-2">
                                    {item?.deposit_type?.type}
                                </td>
                                <td className="px-4 py-2">
                                    {item?.deposit_type?.name}
                                </td>
                                <td className="px-4 py-2">
                                    <select
                                        name="status"
                                        className="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                                <td className="px-4 py-2">
                                    <button
                                        onClick={() => handleDelete(item.id)}
                                        className="flex items-center gap-2 bg-red-500 text-white px-3 py-1.5 rounded-md hover:bg-red-600 transition-all duration-200 text-sm"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            className="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
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
