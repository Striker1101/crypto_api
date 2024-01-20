import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/react";
import axios from "axios";

export default function Plan({ plans }) {
    const token = localStorage.getItem("token");

    const [modalMessage, setModalMessage] = useState("");
    //trade day - current day = days of trade

    /**
     *
     * @param {Date givenDate}
     * @returns int, string
     */

    const [formData, setFormData] = useState([
        {
            name: plans[0].plan,
            percent: parseInt(plans[0].percent),
            duration: plans[0].duration,
            update_at: plans[0].created_at,
        },
        {
            name: plans[1].plan,
            percent: parseInt(plans[1].percent),
            duration: plans[1].duration,
            update_at: plans[1].created_at,
        },

        {
            name: plans[2].plan,
            percent: parseInt(plans[2].percent),
            duration: plans[2].duration,
            update_at: plans[2].created_at,
        },
        {
            name: plans[3].plan,
            percent: parseInt(plans[3].percent),
            duration: plans[3].duration,
            update_at: plans[3].created_at,
        },
        {
            name: plans[4].plan,
            percent: parseInt(plans[4].percent),
            duration: plans[4].duration,
            update_at: plans[4].created_at,
        },
    ]);

    const handleChange = (e, index) => {
        const updatedFormData = [...formData]; // Create a copy of the original array
        updatedFormData[index] = {
            ...updatedFormData[index],
            [e.target.name]: e.target.value,
        };

        setFormData(updatedFormData);
    };

    const handleSubmit = (e, i) => {
        e.preventDefault();
        axios
            .put(`/api/plan/${plans[i].id}`, formData[i], {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setModalMessage(`${formData[i].name} was updated successfully`);
                // Reload
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            })
            .catch((error) => {
                // console.log(error);
                setModalMessage(error.response.data.message);
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            });
    };

    function getDate(update_at) {
        return new Date(update_at).toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true,
        });
    }

    return (
        <div id="container">
            {formData.map((element, index) => {
                return (
                    <div className="container mx-auto mt-8" key={index}>
                        <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                            <div>
                                <h2 className="text-2xl font-semibold mb-4">
                                    {`Edit ${element.name}`}
                                </h2>
                                <p className="text-sm text-gray-600">
                                    {getDate(element.update_at)}
                                </p>
                            </div>

                            <form onSubmit={(e) => handleSubmit(e, index)}>
                                <div className="mb-4">
                                    <label
                                        htmlFor="balance"
                                        className="block text-sm font-medium text-gray-600"
                                    >
                                        Name
                                    </label>
                                    <input
                                        type="text"
                                        id={element.name}
                                        name="name"
                                        defaultValue={element.name}
                                        onChange={(e) => handleChange(e, index)}
                                        className="mt-1 p-2 w-full border rounded-md"
                                        disabled
                                        required
                                    />
                                </div>

                                <div className="mb-4">
                                    <label
                                        htmlFor="balance"
                                        className="block text-sm font-medium text-gray-600"
                                    >
                                        Days
                                    </label>
                                    <input
                                        type="number"
                                        id={element.duration}
                                        name="duration"
                                        min={0}
                                        defaultValue={element.duration}
                                        onChange={(e) => handleChange(e, index)}
                                        className="mt-1 p-2 w-full border rounded-md"
                                        required
                                    />
                                </div>

                                <div className="mb-4">
                                    <label
                                        htmlFor="balance"
                                        className="block text-sm font-medium text-gray-600"
                                    >
                                        Percent
                                    </label>
                                    <input
                                        type="number"
                                        id={element.percent}
                                        name="percent"
                                        defaultValue={element.percent}
                                        max={100}
                                        min={0}
                                        onChange={(e) => handleChange(e, index)}
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
                                        {`Update ${element.name} plan`}
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
            })}
        </div>
    );
}
