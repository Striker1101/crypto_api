import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/react";
import axios from "axios";

export default function Earning({ earning, user_id, apiToken }) {
    if (earning.length < 1) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No Earning Found For this User
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/earning`}
                        method="get"
                        as="button"
                    >
                        Add Earning
                    </Link>
                </div>
            </div>
        );
    }

    const [reload, setreload] = useState(true);
    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");

    const handleDelete = (id) => {
        if (
            confirm(
                "Are you sure you want to delete this earning, Amount woudl be removed from earning"
            )
        )
            axios
                .delete(`/api/earn/${id}`, {
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        // Add any other headers if needed
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((res) => {
                    setModalMessage("earning was deleted successfully");
                    // Redirect to earning details page after successful update

                    setTimeout(() => {
                        setModalMessage("");
                        Inertia.visit(`/dashboard/${user_id}`);
                    }, 2000);
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
            <div className="max-w-xxl mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-2xl font-semibold">Edit Earning</h2>
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/earning`}
                        method="get"
                        as="button"
                    >
                        Add Earning
                    </Link>
                </div>
                <table className="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {earning &&
                            earning.map((item) => (
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
                                    <td>{item.balance}</td>
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
