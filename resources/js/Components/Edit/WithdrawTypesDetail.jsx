import React, { useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { getToken } from "@/Util/transform";
const token = getToken();
export default function WithdrawTypesDetail({
    withdrawTypes,
    formData,
    setFormData,
}) {
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

    function handleDelete(index) {
        // Delete on server
        axios
            .delete(`/api/withdraw_type/${index}`, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`, // Ensure token is defined
                },
            })
            .then((res) => {
                // Remove the deleted item from frontend state
                setFormData((prevData) =>
                    prevData.filter((item) => item.id !== index)
                );
            })
            .catch((err) => {
                console.error("Error deleting withdraw_type:", err);
            });
    }

    return (
        <div id="container">
            {formData.map((element, index) => {
                return (
                    <div className="container mx-auto mt-8" key={index}>
                        <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                            <div className="flex justify-between">
                                <div>
                                    <h2 className="text-2xl font-semibold mb-4">
                                        {`Edit ${element.name}`}
                                    </h2>
                                    <p className="text-sm text-gray-600">
                                        {getDate(element.updated_at)}
                                    </p>
                                </div>

                                <button
                                    onClick={() => {
                                        handleDelete(element.id);
                                    }}
                                    className="bg-red-500 h-10 text-white py-2 px-4 rounded-md hover:bg-red-600"
                                >
                                    delete
                                </button>
                            </div>

                            <WithdrawTypeForm
                                element={element}
                                index={index}
                                formData={formData}
                                setFormData={setFormData}
                            />
                        </div>
                    </div>
                );
            })}
        </div>
    );
}

export function WithdrawTypeForm({
    element,
    index,
    isAdd = false,
    setIsModalOpen,
    setFormData,
    formData,
}) {
    const [addForm, setAddForm] = useState({
        name: "",
        symbol: "",
        image: "",
        currency: "",
        type: "",
        min_limit: 0,
        max_limit: 0,
    });

    const handleAddChange = (e) => {
        setAddForm({ ...addForm, [e.target.name]: e.target.value });
    };

    const handleChange = (e, index) => {
        setFormData((prevFormData) => {
            const updatedFormData = [...prevFormData];
            updatedFormData[index] = {
                ...updatedFormData[index],
                [e.target.name]: e.target.value,
            };
            console.log(updatedFormData); // Log updated state
            return updatedFormData;
        });
    };

    const handleSubmit = (i, id) => {
        console.log(formData, i);
        axios
            .put(`/api/withdraw_type/${id}`, formData[i], {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                toast.success(`${formData[i].name} was updated successfully`);
            })
            .catch((error) => {
                // console.log(error);
                toast.error(error.response.data.message);
            });
    };

    function formReset() {
        setAddForm({
            name: "",
            symbol: "",
            image: "",
            currency: "",
            type: "",
            min_limit: 0,
            max_limit: 0,
        });
    }

    const handleAddSubmit = () => {
        const data = {
            name: addForm.name,
            image: addForm.image,
            symbol: addForm.symbol,
            currency: addForm.currency,
            type: addForm.type,
            min_limit: parseInt(addForm.min_limit),
            max_limit: parseInt(addForm.max_limit),
        };
        axios
            .post(`/api/withdraw_type`, data, {
                headers: {
                    "Content-Type": "application/json",
                    // Add any other headers if needed
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                toast.success(`${addForm.name} was successfully created`);
                formReset();
                setIsModalOpen(false);
            })
            .catch((error) => {
                // console.log(error);
                toast.error(error?.response?.data?.message);
            });
    };

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                isAdd ? handleAddSubmit() : handleSubmit(index, element?.id);
            }}
        >
            <div className="mb-4">
                <label
                    htmlFor="balance"
                    className="block text-sm font-medium text-gray-600"
                >
                    Name
                </label>
                <input
                    type="text"
                    id={isAdd ? "name" : element?.name}
                    name="name"
                    defaultValue={isAdd ? addForm.name : element?.name}
                    placeholder="Enter Name"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            {/* amount of support for this plan */}
            <div className="mb-4">
                <label
                    htmlFor="symbol"
                    className="block text-sm font-medium text-gray-600"
                >
                    Symbol
                </label>
                <input
                    type="text"
                    id={isAdd ? "symbol" : element?.symbol}
                    name="symbol"
                    defaultValue={isAdd ? addForm.symbol : element?.symbol}
                    placeholder="Enter Symbol"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            {/* amount of agent provided to users for this plan */}
            <div className="mb-4">
                <label
                    htmlFor="image"
                    className="block text-sm font-medium text-gray-600"
                >
                    Image
                </label>
                <input
                    type="url"
                    id={isAdd ? "image" : element?.image}
                    name="image"
                    defaultValue={isAdd ? addForm.image : element?.image}
                    placeholder="Enter Image URL"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="currency"
                    className="block text-sm font-medium text-gray-600"
                >
                    Currency
                </label>
                <input
                    type="text"
                    id={isAdd ? "currency" : element?.currency}
                    name="currency"
                    placeholder="Enter Currency"
                    min={0}
                    defaultValue={isAdd ? addForm.currency : element?.currency}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="type"
                    className="block text-sm font-medium text-gray-600"
                >
                    Type
                </label>
                <select
                    type="text"
                    id={isAdd ? "type" : element?.type}
                    name="type"
                    defaultValue={isAdd ? addForm.type : element?.type}
                    placeholder="Enter Type"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                >
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="crypto">Crypto</option>
                </select>
            </div>

            <div className="mb-4">
                <label
                    htmlFor="min_limit"
                    className="block text-sm font-medium text-gray-600"
                >
                    Min Limit
                </label>
                <input
                    type="number"
                    id={isAdd ? "min_limit" : element?.min_limit}
                    name="min_limit"
                    placeholder="Enter Minimum Limit"
                    defaultValue={
                        isAdd ? addForm.min_limit : element?.min_limit
                    }
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="max_limit"
                    className="block text-sm font-medium text-gray-600"
                >
                    Max Limit
                </label>
                <input
                    type="number"
                    id={isAdd ? "max_limit" : element?.max_limit}
                    name="max_limit"
                    placeholder="Enter Maximum Limit"
                    defaultValue={
                        isAdd ? addForm.max_limit : element?.max_limit
                    }
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
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
                    {isAdd ? "Add Wallet" : `Update ${element?.name} name`}
                </button>
            </div>
        </form>
    );
}
