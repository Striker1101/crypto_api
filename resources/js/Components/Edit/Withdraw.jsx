import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { InertiaLink } from "@inertiajs/inertia-react";
import { Link } from "@inertiajs/react";
import axios from "axios";

export default function Withdraw({ account, withdraw, user_id, apiToken }) {
    if (withdraw.length < 1) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No withdraw Found For this User
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/withdraw`}
                        method="get"
                        as="button"
                    >
                        Add withdraw
                    </Link>
                </div>
            </div>
        );
    }

    const token = localStorage.getItem("token");
    const [reload, setreload] = useState(true);
    const [modalMessage, setModalMessage] = useState("");

    const handleToggle = (id) => {
        withdraw.map((item) => {
            if (item.id == id) {
                item.status === 0 ? (item.status = 1) : (item.status = 0);

                //reload
                setreload(!reload);

                //submit
                handleSubmit(item);
            }
        });
    };

    const handleSubmit = (formData) => {
        console.log(withdraw);
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
        if (
            confirm(
                "Are you sure you want to delete this withdraw, if status is true Amount would be Added to Account Balance "
            )
        )
            axios
                .delete(`/api/withdraw/${id}`, {
                    headers: {
                        "Content-Type": "application/json",
                        // Add any other headers if needed
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((res) => {
                    setModalMessage("withdraw was deleted successfully");
                    // Redirect to withdraw details page after successful update
                    Inertia.visit(`/dashboard/${user_id}`);

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

    const check = (balance, earning, amount) => {
        return parseInt(balance) + parseInt(earning) > parseInt(amount);
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
                <table className="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Destination</th>
                            <th>Withdrawal Type</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {withdraw &&
                            withdraw.map((item) => (
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
                                    <td>{item.name}</td>
                                    <td>{item.amount}</td>
                                    <td>{item.currency}</td>
                                    <td>{item.destination}</td>
                                    <td>{item.withdrawal_type}</td>
                                    <td>
                                        {/* check if user account balance and earning is greater than the amount to withdraw */}
                                        <label
                                            title={
                                                check(
                                                    account.balance,
                                                    account.earning,
                                                    item.amount
                                                )
                                                    ? item.status === 1
                                                        ? check(
                                                              account.balance,
                                                              account.earning,
                                                              item.amount
                                                          )
                                                            ? "Increase Balance"
                                                            : "please add money to balance"
                                                        : "withdraw From Balance"
                                                    : item.status == 1
                                                    ? "Increase Balance"
                                                    : "please add money to balance"
                                            }
                                            className="switch"
                                        >
                                            <input
                                                type="checkbox"
                                                checked={item.status == 1}
                                                disabled={
                                                    check(
                                                        account.balance,
                                                        account.earning,
                                                        item.amount
                                                    )
                                                        ? item.status === 1
                                                            ? check(
                                                                  account.balance,
                                                                  account.earning,
                                                                  item.amount
                                                              )
                                                                ? false
                                                                : true
                                                            : false
                                                        : item.status == 1
                                                        ? false
                                                        : true
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
