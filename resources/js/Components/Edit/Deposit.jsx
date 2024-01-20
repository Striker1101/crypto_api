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

    console.log(deposit);

    const [reload, setreload] = useState(true);
    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");
    const handleToggle = (id) => {
        deposit.map((item) => {
            if (item.id == id) {
                item.status === "pending"
                    ? (item.status = "completed")
                    : (item.status = "pending");

                //reload
                setreload(!reload);

                console.log(item);

                //submit
                handleSubmit(item);
            }
        });
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
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/deposit`}
                        method="get"
                        as="button"
                    >
                        Add Deposit
                    </Link>
                </div>
                <table className="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Wallet Address</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {deposit &&
                            deposit.map((item) => (
                                <tr key={item.id}>
                                    <td>
                                        {" "}
                                        <p className="text-sm text-gray-600">
                                            {new Date(
                                                item.updated_at
                                            ).toLocaleString("en-US", {
                                                year: "numeric",
                                                month: "short",
                                                day: "numeric",
                                                hour: "numeric",
                                                minute: "numeric",
                                                hour12: true,
                                            })}
                                        </p>
                                    </td>
                                    <td>{item.amount}</td>
                                    <td>{item.currency}</td>
                                    <td>{item.wallet_address}</td>
                                    <td>
                                        <label className="switch">
                                            <input
                                                type="checkbox"
                                                checked={
                                                    item.status === "completed"
                                                }
                                                onChange={() =>
                                                    handleToggle(item.id)
                                                } // Add your toggle handler function
                                                className="hidden"
                                            />
                                            <span className="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button
                                            onClick={() => {
                                                handleDelete(item.id);
                                            }}
                                            className="bg-red-500 text-white px-2 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:shadow-outline-red active:bg-red-800"
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
