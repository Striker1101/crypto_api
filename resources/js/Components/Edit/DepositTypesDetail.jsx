import React, { useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { getToken } from "@/Util/transform";
const token = getToken();
export default function DepositTypesDetail({
    depositTypes,
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
            .delete(`/api/deposit_type/${index}`, {
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

                            <DepositTypeForm
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

export function DepositTypeForm({
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
        details: {},
        min_limit: 0,
        max_limit: 0,
        details: {
            wallet_address: "",
            bank_name: "",
            account_name: "",
            account_number: "",
            bank_branch: "",
            paypal_email: "",
            network: "",
        },
    });

    const handleAddChange = (e) => {
        const { name, value } = e.target;

        if (name in addForm.details) {
            setAddForm({
                ...addForm,
                details: {
                    ...addForm.details,
                    [name]: value,
                },
            });
        } else {
            setAddForm({
                ...addForm,
                [name]: value,
            });
        }
    };

    const handleChange = (e, index) => {
        const { name, value } = e.target;

        setFormData((prevFormData) => {
            const updatedFormData = [...prevFormData];

            if (name in addForm.details) {
                updatedFormData[index] = {
                    ...updatedFormData[index],
                    details: {
                        ...updatedFormData[index].details,
                        [name]: value,
                    },
                };
            } else {
                updatedFormData[index] = {
                    ...updatedFormData[index],
                    [name]: value,
                };
            }

            console.log(updatedFormData);
            return updatedFormData;
        });
    };

    const handleSubmit = (i, id) => {
        console.log(formData, i);
        axios
            .put(`/api/deposit_type/${id}`, formData[i], {
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
            details: {
                wallet_address: "",
                bank_name: "",
                account_name: "",
                account_number: "",
                bank_branch: "",
                paypal_email: "",
                network: "",
            },
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
            details: {
                wallet_address: addForm.details.wallet_address,
                bank_name: addForm.details.bank_name,
                account_name: addForm.details.account_name,
                account_number: addForm.details.account_number,
                bank_branch: addForm.details.bank_branch,
                paypal_email: addForm.details.paypal_email,
                network: addForm.details.network,
            },
        };
        axios
            .post(`/api/deposit_type`, data, {
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
                    <option value="">Select Type</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="crypto">Crypto</option>
                    <option value="paypal">Paypal</option>
                </select>
            </div>

            {/* for bank transfer */}
            <div>
                {(element?.type == "bank_transfer" ||
                    addForm.type == "bank_transfer") && (
                    <>
                        <div className="mb-4">
                            <label
                                htmlFor="bank_name"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Bank Name
                            </label>
                            <input
                                type="text"
                                id={
                                    isAdd
                                        ? "bank_name"
                                        : element?.details?.bank_name
                                }
                                name="bank_name"
                                placeholder="Enter Bank Name"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm?.details?.bank_name
                                        : element?.details?.bank_name
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>

                        <div className="mb-4">
                            <label
                                htmlFor="account_name"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Account Name
                            </label>
                            <input
                                type="text"
                                id={
                                    isAdd
                                        ? "account_name"
                                        : element?.details?.account_name
                                }
                                name="account_name"
                                placeholder="Enter Account Name"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm?.details?.account_name
                                        : element?.details?.account_name
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>

                        <div className="mb-4">
                            <label
                                htmlFor="account_number"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Account Number
                            </label>
                            <input
                                type="number"
                                id={
                                    isAdd
                                        ? "account_number"
                                        : element?.details?.account_number
                                }
                                name="account_name"
                                placeholder="Enter Account Number"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm?.details?.account_number
                                        : element?.details?.account_number
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>

                        <div className="mb-4">
                            <label
                                htmlFor="bank_branch"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Bank Branch
                            </label>
                            <input
                                type="text"
                                id={
                                    isAdd
                                        ? "bank_branch"
                                        : element?.details?.bank_branch
                                }
                                name="bank_branch"
                                placeholder="Enter Bank Branch"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm?.details?.bank_branch
                                        : element?.details?.bank_branch
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>
                    </>
                )}
            </div>

            {/* for crypto transfer */}
            <div>
                {(element?.type == "crypto" || addForm.type == "crypto") && (
                    <>
                        <div className="mb-4">
                            <label
                                htmlFor="wallet_address"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Wallet Address
                            </label>
                            <input
                                type="text"
                                id={
                                    isAdd
                                        ? "wallet_address"
                                        : element?.details?.wallet_address
                                }
                                name="wallet_address"
                                placeholder="Enter Wallet Address"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm.details.wallet_address
                                        : element?.details?.wallet_address
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>

                        <div className="mb-4">
                            <label
                                htmlFor="network"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Wallet Network
                            </label>
                            <input
                                type="text"
                                id={
                                    isAdd
                                        ? "network"
                                        : element?.details?.network
                                }
                                name="network"
                                placeholder="Enter Wallet Network"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm.details.network
                                        : element?.details?.network
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>
                    </>
                )}
            </div>

            {/* for paypal transfer */}
            <div>
                {(element?.type == "paypal" || addForm.type == "paypal") && (
                    <>
                        <div className="mb-4">
                            <label
                                htmlFor="paypal_email"
                                className="block text-sm font-medium text-gray-600"
                            >
                                Paypal Email
                            </label>
                            <input
                                type="email"
                                id={
                                    isAdd
                                        ? "paypal_email"
                                        : element?.details?.paypal_email
                                }
                                name="paypal_email"
                                placeholder="Enter Paypal Email"
                                min={0}
                                defaultValue={
                                    isAdd
                                        ? addForm.details.paypal_email
                                        : element?.details?.paypal_email
                                }
                                onChange={(e) =>
                                    isAdd
                                        ? handleAddChange(e)
                                        : handleChange(e, index)
                                }
                                className="mt-1 p-2 w-full border rounded-md"
                                required
                            />
                        </div>
                    </>
                )}
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
                    {isAdd ? "Add Deposit Type" : `Update ${element?.name}`}
                </button>
            </div>
        </form>
    );
}
