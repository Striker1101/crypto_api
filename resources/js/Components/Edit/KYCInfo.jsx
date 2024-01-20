import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default function KYCInfo({ kyc_info, apiToken }) {
    if (kyc_info == null) {
        return;
    }

    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");
    const [formData, setFormData] = useState({
        user_id: kyc_info.user_id,
        ssn: kyc_info.ssn,
        // Add other user fields as needed
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        axios
            .put(`/api/kyc_info/${kyc_info.id}`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                console.log(res);

                setModalMessage("kyc_info was updated successfully");
                // Redirect to kyc_info details page after successful update
                Inertia.visit(`/dashboard/${kyc_info.user_id}`);

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

    const formattedDate = new Date(kyc_info.updated_at).toLocaleString(
        "en-US",
        {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true,
        }
    );

    return (
        <div className="container mx-auto mt-8">
            {kyc_info == null ? (
                ""
            ) : (
                <div>
                    <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                        <div>
                            <h2 className="text-2xl font-semibold mb-4">
                                View kyc_info
                            </h2>
                            <p className="text-sm text-gray-600">
                                {formattedDate}
                            </p>
                        </div>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label
                                    htmlFor="ssn"
                                    className="block text-sm font-medium text-gray-600"
                                >
                                    SSN
                                </label>
                                <input
                                    type="text"
                                    id="ssn"
                                    name="ssn"
                                    disabled
                                    defaultValue={formData.ssn}
                                    onChange={handleChange}
                                    className="mt-1 p-2 w-full border rounded-md"
                                    required
                                />
                            </div>

                            {/* Add other user fields as needed */}

                            <div className="mt-4">
                                <button
                                    type="submit"
                                    disabled
                                    className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                                >
                                    No Edit Allowed
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
            )}
        </div>
    );
}
