import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import React, { useState, useEffect } from "react";

import Plan from "@/Components/Edit/Plan";

export default function UsersPlan({ auth, plans }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Plans Edit
                </h2>
            }
        >
            <Plan plans={plans} />
        </AuthenticatedLayout>
    );
}
