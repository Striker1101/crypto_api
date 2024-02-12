import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
export default function CreateNotify({ user_id }) {
    const [modalMessage, setModalMessage] = useState("");
    const token = localStorage.getItem("token");
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({
        user_id: user_id,
        content: "",
        read: 0,
        header: "",
        footer: "",
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
        setLoading(true);
        axios
            .post(`/api/notify`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setModalMessage("mail was created successfully");
                // Redirect to deposit details page after successful update

                form.current.reset();
                setFormData({
                    user_id: user_id,
                    content: "",
                    read: 0,
                    header: "",
                    footer: "",
                });

                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            })
            .catch((error) => {
                setModalMessage(error.response.data.message);
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            })
            .finally(() => {
                setLoading(false);
            });
    };

    return (
        <div className="container mx-auto mt-8">
            <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-2xl font-semibold">Send Mail</h2>
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
                            htmlFor="content"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Content
                        </label>
                        <textarea
                            type="number"
                            id="content"
                            name="content"
                            defaultValue={formData.content}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="header"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Header
                        </label>
                        <input
                            type="text"
                            id="header"
                            name="header"
                            defaultValue={formData.header}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="footer"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Footer
                        </label>
                        <input
                            type="text"
                            id="footer"
                            name="footer"
                            defaultValue={formData.footer}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>

                    {/* Add other user fields as needed */}

                    <div className="mt-4">
                        <button
                            type="submit"
                            className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                            disabled={loading} // Disable the button when loading
                        >
                            {loading ? "Hold..." : "Send Notification"}
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
