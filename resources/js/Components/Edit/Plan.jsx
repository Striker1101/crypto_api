import React, { useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { getToken } from "@/Util/transform";
const token = getToken();
export default function Plan({ plans, formData, setFormData }) {
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
            .delete(`/api/plan/${index}`, {
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
                console.error("Error deleting plan:", err);
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

                            <PlanForm
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

export function PlanForm({
    element,
    index,
    isAdd = false,
    setIsModalOpen,
    setFormData,
    formData,
}) {
    const [addForm, setAddForm] = useState({
        agent: 0,
        amount: 0,
        duration: 0,
        name: "",
        percent: 0,
        support: 0,
        type: "",
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
            .put(`/api/plan/${id}`, formData[i], {
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
            agent: 0,
            amount: 0,
            duration: 0,
            name: "",
            percent: 0,
            support: 0,
            type: "",
        });
    }

    const handleAddSubmit = () => {
        const data = {
            agent: parseInt(addForm.agent),
            amount: parseInt(addForm.amount),
            duration: parseInt(addForm.duration),
            name: addForm.name,
            percent: parseInt(addForm.percent),
            support: parseInt(addForm.support),
            type: addForm.type,
        };
        axios
            .post(`/api/plan`, data, {
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

            <div className="mb-4">
                <label
                    htmlFor="duration"
                    className="block text-sm font-medium text-gray-600"
                >
                    Days
                </label>
                <input
                    type="number"
                    id={isAdd ? "duration" : element?.duration}
                    name="duration"
                    placeholder="Enter Duration"
                    min={0}
                    defaultValue={isAdd ? addForm.duration : element?.duration}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="percent"
                    className="block text-sm font-medium text-gray-600"
                >
                    Percent
                </label>
                <input
                    type="number"
                    id={isAdd ? "percent" : element?.percent}
                    name="percent"
                    defaultValue={isAdd ? addForm.percent : element?.percent}
                    max={100}
                    placeholder="Enter Percent"
                    min={0}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="amount"
                    className="block text-sm font-medium text-gray-600"
                >
                    Amount
                </label>
                <input
                    type="number"
                    id={isAdd ? "amount" : element?.amount}
                    name="amount"
                    placeholder="Enter Amount"
                    defaultValue={isAdd ? addForm.amount : element?.amount}
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
                    htmlFor="support"
                    className="block text-sm font-medium text-gray-600"
                >
                    Support
                </label>
                <input
                    type="number"
                    id={isAdd ? "support" : element?.support}
                    name="support"
                    defaultValue={isAdd ? addForm.support : element?.support}
                    max={100}
                    placeholder="Enter Amount of  Support"
                    min={0}
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
                    htmlFor="agent"
                    className="block text-sm font-medium text-gray-600"
                >
                    Agent
                </label>
                <input
                    type="number"
                    id={isAdd ? "agent" : element?.agent}
                    name="agent"
                    defaultValue={isAdd ? addForm.agent : element?.agent}
                    max={100}
                    placeholder="Enter Amount of Agent"
                    min={0}
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
                <input
                    type="text"
                    id={isAdd ? "type" : element?.type}
                    name="type"
                    placeholder="Enter Plan Type"
                    defaultValue={isAdd ? addForm.type : element?.type}
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
                    {isAdd ? "Add Plan" : `Update ${element?.name} name`}
                </button>
            </div>
        </form>
    );
}
