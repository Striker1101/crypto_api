import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import React, { useState, useEffect } from "react";
import UserDetails from "@/Components/Edit/UserDetails";
import Account from "@/Components/Edit/Account";
import KYCInfo from "@/Components/Edit/KYCInfo";
import Deposit from "@/Components/Edit/Deposit";
import Withdraw from "@/Components/Edit/Withdraw";
import Notification from "@/Components/Edit/Notification";

export default function EditUser({ auth, user }) {
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
                <KYCInfo kyc_info={user.kyc_info} />
                <Deposit deposit={user.deposit} user_id={user.id} />
                <Withdraw withdraw={user.withdraws} user_id={user.id} />
                <Notification notification={user.notifications} />
            </div>
        </AuthenticatedLayout>
    );
}
