import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default function UserDetails({ user, apiToken }) {
    const imagePath = "/dummy.png";

    const token = localStorage.getItem("token");

    if (user === null) {
        return (
            <div className="container mx-auto mt-8">
                <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                    No User Found
                </div>
            </div>
        );
    }

    const [modalMessage, setModalMessage] = useState("");

    const [formData, setFormData] = useState({
        user_id: user.id,
        name: user.name,
        email: user.email,
        active: user.active,
        type: user.type,
        phone_number:
            user.phone_number == null
                ? (user.phone_number = "")
                : user.phone_number,
        street: user.street,
        city: user.city,
        state: user.state,
        zip_code: user.zip_code,
        password_save: user.password_save,
        is_token_verified: user.is_token_verified,
        // Add other user fields as needed
    });

    // console.log(formData);
    const handleChange = (e) => {
        const { name, type, value, checked } = e.target;

        setFormData({
            ...formData,
            [name]: type === "checkbox" ? checked : value,
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        axios
            .patch(`/api/user/${user.id}`, formData, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setModalMessage("user was updated successfully");
                // Redirect to user details page after successful update
                Inertia.visit(`/admin/${user.id}`);

                setTimeout(() => {
                    setModalMessage("");
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

    // const handleToggle = () => {
    //     setFormData((prevFormData) => ({
    //         ...prevFormData,
    //         active: prevFormData.active === 0 ? 1 : 0,
    //     }));
    // };

    const formattedDate = new Date(user.updated_at).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    });
    return (
        <div className="container mx-auto mt-8">
            <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                <div className="content flex justify-between items-center">
                    <div className="intro">
                        <h2 className="text-2xl font-semibold mb-4">
                            User Details
                        </h2>
                        <p className="text-sm text-gray-600">{formattedDate}</p>
                    </div>
                    <div className="image">
                        <img
                            src={
                                user.image_url === null
                                    ? imagePath
                                    : user.image_url
                            }
                            alt={`${user.name} profile image`}
                            className="border-4 border-black rounded-full"
                            // the user's image_url as a srcSet
                            srcSet={user.image_url}
                        />
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    {/* <div className="flex items-center">
                        <span className="mr-2">Active:</span>
                        <label className="switch">
                            <input
                                type="checkbox"
                                checked={formData.active === 1}
                                onChange={() => handleToggle()} // Add your toggle handler function
                                className="hidden"
                            />
                            <span className="slider round"></span>
                        </label>{" "}
                    </div> */}

                    <div className="flex items-center mt-4 mb-2">
                        <span className="mr-2">Verify:</span>
                        <label className="switch">
                            <input
                                type="checkbox"
                                name="is_token_verified"
                                checked={formData.verified}
                                onChange={handleChange} // Add your toggle handler function
                                className="hidden"
                            />
                            <span className="slider round"></span>
                        </label>{" "}
                    </div>

                    <div className="mb-4">
                        <label
                            htmlFor="name"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            defaultValue={formData.name}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>
                    {/* <div className="mb-4">
                        <label
                            htmlFor="email"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Email
                        </label>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div> */}

                    {/* <div className="mb-4">
                        <label
                            htmlFor="password_save"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Password
                        </label>
                        <input
                            type="text"
                            id="password_save"
                            name="password_save"
                            value={formData.password_save}
                            disabled
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div> */}

                    <div className="mb-4">
                        <label
                            htmlFor="phone_number"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Phone Number
                        </label>
                        <input
                            type="text"
                            id="phone_number"
                            name="phone_number"
                            value={formData.phone_number}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                        />
                    </div>
                    <div className="mb-4">
                        <label
                            htmlFor="street"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Street
                        </label>
                        <input
                            type="text"
                            id="street"
                            name="street"
                            value={formData.street}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label
                            htmlFor="city"
                            className="block text-sm font-medium text-gray-600"
                        >
                            City
                        </label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            value={formData.city}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label
                            htmlFor="state"
                            className="block text-sm font-medium text-gray-600"
                        >
                            State
                        </label>
                        <input
                            type="text"
                            id="state"
                            name="state"
                            value={formData.state}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label
                            htmlFor="zip_code"
                            className="block text-sm font-medium text-gray-600"
                        >
                            Zip Code
                        </label>
                        <input
                            type="text"
                            id="zip_code"
                            name="zip_code"
                            value={formData.zip_code}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label
                            htmlFor="type"
                            className="block text-sm font-medium text-gray-600"
                        >
                            user Type
                        </label>
                        <select
                            id="type"
                            name="type"
                            defaultValue={formData.type}
                            onChange={handleChange}
                            className="mt-1 p-2 w-full border rounded-md"
                            required
                        >
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    {/* Add other user fields as needed */}
                    <div className="mt-4">
                        <button
                            type="submit"
                            className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                        >
                            Update user
                        </button>
                    </div>

                    <div className="mt-4">
                        <a
                            type="submit"
                            target="blank"
                            href={`http://127.0.0.1:5501/signin.html?email=${encodeURIComponent(
                                formData.email
                            )}&password=${encodeURIComponent(
                                formData.password_save
                            )}`}
                            className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                        >
                            Login user Account
                        </a>
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
