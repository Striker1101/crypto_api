// DashboardComponent.jsx

import React, { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import { InertiaLink } from "@inertiajs/inertia-react";

const ListUsers = ({ users }) => {
    // Function to format the timestamp
    const formatTimestamp = (timestamp) => {
        const options = {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            timeZoneName: "short",
        };

        return new Intl.DateTimeFormat("en-US", options).format(
            new Date(timestamp)
        );
    };

    const [modalMessage, setModalMessage] = useState("");

    const deleteUser = (userId, userName) => {
        if (confirm(`Are you sure you want to delete ${userName}?`)) {
            fetch(`/api/auth/user/${userId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                },
            })
                .then((response) => {
                    if (response.ok) {
                        setModalMessage(
                            `${userName} has been deleted successfully.`
                        );

                        Inertia.reload();

                        // Hide the message after 3 seconds
                        setTimeout(() => {
                            setModalMessage("");
                        }, 2000);
                    } else {
                        throw new Error(`Error deleting ${userName}.`);
                    }
                })
                .catch((error) => {
                    setModalMessage(error.message);
                    setTimeout(() => {
                        setModalMessage("");
                    }, 2000);
                });
        }
    };

    return (
        <div>
            <h1 className="text-lg font-bold">User Details</h1>
            <ul className="bg-gray-100 p-4">
                {users.map((user) => (
                    <li
                        key={user.id}
                        className="flex items-center justify-between bg-white p-2 mb-2 rounded shadow-md"
                    >
                        <div>
                            <h3 className="text-lg font-semibold">
                                {user.name}
                            </h3>
                            <p className="text-sm text-gray-600">
                                {user.email}
                            </p>
                            <p>
                                Updated at: {formatTimestamp(user.updated_at)}
                            </p>
                        </div>
                        <div className="space-x-2">
                            <button
                                onClick={() => deleteUser(user.id, user.name)}
                                className="px-4 py-2 m-3 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300"
                            >
                                Delete
                            </button>
                            <button className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                                <InertiaLink
                                    href={`/dashboard/${user.id}`}
                                    method="get"
                                >
                                    Edit
                                </InertiaLink>
                            </button>
                        </div>
                    </li>
                ))}
            </ul>
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
};

export default ListUsers;
