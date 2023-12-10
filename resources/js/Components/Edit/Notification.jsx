import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { InertiaLink } from "@inertiajs/inertia-react";
import { Link } from "@inertiajs/react";
import axios from "axios";

export default function Notification({ notification, user_id }) {
    if (notification === null) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No notification Found For this User
                </div>
            </div>
        );
    }

    return (
        <div className="container mx-auto mt-8">
            <div className="max-w-xl mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-2xl font-semibold">
                        Notification Messages
                    </h2>
                    <Link
                        className="bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        href={`/dashboard/${user_id}/notification`}
                        method="get"
                        as="button"
                    >
                        Add notification
                    </Link>
                </div>
                <table className="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Read</th>
                            <th>Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        {notification &&
                            notification.map((item) => (
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
                                    <td>{item.read}</td>
                                    <td>
                                        {" "}
                                        <p>{item.content}</p>{" "}
                                    </td>
                                </tr>
                            ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
