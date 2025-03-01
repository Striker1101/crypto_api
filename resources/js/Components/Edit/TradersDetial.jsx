import React, { useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { getToken } from "@/Util/transform";
import ProfilePictureUpload from "../ProfilePictureUpload";
const token = getToken();
export default function TradersDetail({ formData, setFormData }) {
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
            .delete(`/api/trader/${index}`, {
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

                            <TraderForm
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

export function TraderForm({
    element,
    index,
    isAdd = false,
    setIsModalOpen,
    setFormData,
    formData,
}) {
    const [addForm, setAddForm] = useState({
        name: "",
        position: "",
        profile_picture: "",
        rating: "",
        ROI: 0,
        PnL: 0,
        investment: 0,
        ranking: 0,
        display: true,
    });

    const handleAddChange = (e) => {
        const { name, value, type, checked } = e.target;
        setAddForm((prev) => ({
            ...prev,
            [name]: type === "checkbox" ? checked : value,
        }));
    };

    const handleProfilePicture = (file) => {
        setAddForm((prev) => ({
            ...prev,
            profile_picture: file,
        }));
    };

    const handleAddProfilePicture = (file, index) => {
        if (!file) return;

        setFormData((prevFormData) => {
            const updatedFormData = [...prevFormData];
            updatedFormData[index] = {
                ...updatedFormData[index],
                profile_picture: file, //Store the file object
            };
            console.log(updatedFormData); // Log updated state
            return updatedFormData;
        });
    };

    const handleChange = (e, index) => {
        const { name, type, value, checked } = e.target;

        setFormData((prevFormData) => {
            const updatedFormData = [...prevFormData];
            updatedFormData[index] = {
                ...updatedFormData[index],
                [name]: type === "checkbox" ? checked : value,
            };
            console.log(updatedFormData); // Log updated state
            return updatedFormData;
        });
    };

    const handleSubmit = (i, id) => {
        console.log(formData[i]);

        let isFileUpload = formData[i]?.profile_picture instanceof File;
        let payload;
        let headers = {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        };

        if (isFileUpload) {
            // Send as multipart/form-data
            payload = new FormData();
            payload.append("name", formData[i]?.name);
            payload.append("position", formData[i]?.position);
            payload.append("profile_picture", formData[i]?.profile_picture); // File upload
            payload.append("rating", formData[i]?.rating);
            payload.append("ROI", parseInt(formData[i]?.ROI));
            payload.append("PnL", parseInt(formData[i]?.PnL));
            payload.append("investment", parseInt(formData[i]?.investment));
            payload.append("ranking", parseInt(formData[i]?.ranking));
            payload.append(
                "display",
                formData[i]?.display == 0 || formData[i]?.display === true
                    ? "true"
                    : "false"
            ); // Ensure boolean is string for consistency

            headers["Content-Type"] = "multipart/form-data";
        } else {
            // Send as JSON
            payload = JSON.stringify({
                name: formData[i]?.name,
                position: formData[i]?.position,
                profile_picture: formData[i]?.profile_picture, // Existing URL
                rating: formData[i]?.rating,
                ROI: parseInt(formData[i]?.ROI),
                PnL: parseInt(formData[i]?.PnL),
                investment: parseInt(formData[i]?.investment),
                ranking: parseInt(formData[i]?.ranking),
                display:
                    formData[i]?.display == 0 || formData[i]?.display === true
                        ? true
                        : false,
            });

            headers["Content-Type"] = "application/json";
        }

        axios
            .put(`/api/trader/${id}`, payload, { headers })
            .then((res) => {
                toast.success(`${formData[i].name} was updated successfully`);
            })
            .catch((error) => {
                toast.error(
                    error.response?.data?.message || "Something went wrong"
                );
            });
    };

    function formReset() {
        setAddForm({
            name: "",
            position: "",
            profile_picture: "",
            rating: "",
            ROI: 0,
            PnL: 0,
            investment: 0,
            ranking: "",
            display: true,
        });
    }

    const handleAddSubmit = () => {
        const formData = new FormData();
        formData.append("name", addForm.name);
        formData.append("position", addForm.position);
        formData.append("profile_picture", addForm.profile_picture); // Ensure this is a File object
        formData.append("rating", addForm.rating);
        formData.append("ROI", parseInt(addForm.ROI)); // No need for parseInt() as FormData treats all values as strings
        formData.append("PnL", parseInt(addForm.PnL));
        formData.append("investment", parseInt(addForm.investment));
        formData.append("ranking", parseInt(addForm.ranking));
        formData.append(
            "display",
            addForm.display == 0 || addForm.display === true ? true : false
        ); // Ensure boolean is sent as string

        axios
            .post(`/api/trader`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                toast.success(`${addForm.name} was successfully created`);
                formReset();
                setIsModalOpen(false);
            })
            .catch((error) => {
                toast.error(error?.response?.data?.message);
            });
    };

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                isAdd ? handleAddSubmit() : handleSubmit(index, element?.id);
            }}
            encType="multipart/form-data"
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
                    htmlFor="position"
                    className="block text-sm font-medium text-gray-600"
                >
                    Position
                </label>
                <input
                    type="text"
                    id={isAdd ? "position" : element?.position}
                    name="position"
                    defaultValue={isAdd ? addForm.position : element?.position}
                    placeholder="Enter Position"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <ProfilePictureUpload
                profilePicture={
                    addForm.profile_picture || element?.profile_picture
                }
                onChange={(file) => {
                    handleProfilePicture(file) ||
                        handleAddProfilePicture(file, index);
                }}
            />

            <div className="mb-4">
                <label
                    htmlFor="rating"
                    className="block text-sm font-medium text-gray-600"
                >
                    Rating
                </label>
                <input
                    type="text"
                    id={isAdd ? "rating" : element?.rating}
                    name="rating"
                    placeholder="Enter Rating"
                    min={0}
                    defaultValue={isAdd ? addForm.rating : element?.rating}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="ranking"
                    className="block text-sm font-medium text-gray-600"
                >
                    Ranking
                </label>
                <input
                    type="number"
                    id={isAdd ? "ranking" : element?.ranking}
                    name="ranking"
                    defaultValue={isAdd ? addForm.ranking : element?.ranking}
                    placeholder="Enter Ranking"
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="ROI"
                    className="block text-sm font-medium text-gray-600"
                >
                    ROI
                </label>
                <input
                    type="number"
                    id={isAdd ? "ROI" : element?.ROI}
                    name="ROI"
                    placeholder="Enter ROI"
                    defaultValue={isAdd ? addForm.ROI : element?.ROI}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="PnL"
                    className="block text-sm font-medium text-gray-600"
                >
                    PNL
                </label>
                <input
                    type="number"
                    id={isAdd ? "PnL" : element?.PnL}
                    name="PnL"
                    placeholder="Enter PNL"
                    defaultValue={isAdd ? addForm.PnL : element?.PnL}
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-full border rounded-md"
                    required
                />
            </div>

            <div className="mb-4">
                <label
                    htmlFor="investment"
                    className="block text-sm font-medium text-gray-600"
                >
                    Investment
                </label>
                <input
                    type="number"
                    id={isAdd ? "investment" : element?.investment}
                    name="investment"
                    placeholder="Enter Investment"
                    defaultValue={
                        isAdd ? addForm.investment : element?.investment
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
                    htmlFor="display"
                    className="block text-sm font-medium text-gray-600"
                >
                    Display
                </label>
                <input
                    type="checkbox"
                    id={isAdd ? "display" : element?.display}
                    name="display"
                    placeholder="Enter Display"
                    defaultChecked={
                        isAdd
                            ? addForm.display == 0 || addForm.display == true
                                ? true
                                : false
                            : element?.display == 0
                            ? true
                            : false
                    }
                    onChange={(e) =>
                        isAdd ? handleAddChange(e) : handleChange(e, index)
                    }
                    className="mt-1 p-2 w-auto border rounded-md"
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
