import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import React, { useState } from "react";
import WithdrawTypesDetail, {
    WithdrawTypeForm,
} from "@/Components/Edit/WithdrawTypesDetail";

export default function UsersPlan({ auth, withdrawTypes }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [formData, setFormData] = useState(withdrawTypes);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Withdraw Types Edit
                    </h2>
                    <button
                        onClick={() => setIsModalOpen(true)}
                        className="bg-green-500 h-10 text-white py-2 px-4 rounded-md hover:bg-green-600"
                    >
                        New WithdrawType
                    </button>
                </div>
            }
        >
            <WithdrawTypesDetail
                withdrawTypes={withdrawTypes}
                formData={formData}
                setFormData={setFormData}
            />

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div className="bg-white p-6 rounded-lg shadow-lg w-96 h-[500px] overflow-y-auto">
                        <div className="flex justify-between">
                            <h3 className="text-lg font-semibold mb-4">
                                Create New Withdraw Type
                            </h3>

                            <button
                                onClick={() => setIsModalOpen(false)}
                                className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                            >
                                Close
                            </button>
                        </div>
                        {/* Add form or content here */}
                        <WithdrawTypeForm
                            isAdd={true}
                            setIsModalOpen={setIsModalOpen}
                        />
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
