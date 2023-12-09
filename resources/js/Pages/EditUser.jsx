import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import React, { useState, useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import UserDetails from "@/Components/Edit/UserDetails";
import Account from "@/Components/Edit/Account";

export default function EditUser({ auth, user, account }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Edit - {user.name}
                </h2>
            }
        >
            <div>
                <UserDetails user={user} />
                <Account account={user.account} />
            </div>
        </AuthenticatedLayout>
    );
}
