import React from "react";

export default function PlanEdit({
    element,
    modalMessage,
    index,
    handleChange,
    handleSubmit,
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
    return (
        <div className="container mx-auto mt-8" key={index}>
            <div className="max-w-md mx-auto bg-white p-8 border shadow-md rounded-md">
                <div>
                    <h2 className="text-2xl font-semibold mb-4">
                        {`Edit ${element.plan}`}
                    </h2>
                    <p className="text-sm text-gray-600">
                        {getDate(element.updated_at)}
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
                            defaultValue={element.plan}
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
                            type="text"
                            id={element.duration}
                            name="duration"
                            max={0}
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
                            maxLength={3}
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
                            {`Update ${element.plan} plan`}
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
