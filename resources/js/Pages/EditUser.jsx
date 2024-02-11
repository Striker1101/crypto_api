import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import React, { useState, useEffect } from "react";
import UserDetails from "@/Components/Edit/UserDetails";
import Account from "@/Components/Edit/Account";
import KYCInfo from "@/Components/Edit/KYCInfo";
import Deposit from "@/Components/Edit/Deposit";
import Withdraw from "@/Components/Edit/Withdraw";
import Notification from "@/Components/Edit/Notification";
import Earning from "@/Components/Edit/Earning";
import Investment from "@/Components/Edit/Investment";

export default function EditUser({ auth, user }) {
    let apiToken = "";
    useEffect(() => {
        apiToken = localStorage.getItem("token");
    }, []);

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
                <UserDetails user={user} apiToken={apiToken} />
                <Account account={user.account} apiToken={apiToken} />
                <KYCInfo kyc_info={user.kyc_info} apiToken={apiToken} />
                <Deposit
                    deposit={user.deposit}
                    apiToken={apiToken}
                    account={{
                        balance: user.account.balance,
                        earning: user.account.earning,
                        bonus: user.account.bonus,
                    }}
                    user_id={user.id}
                />
                <Withdraw
                    withdraw={user.withdraws}
                    apiToken={apiToken}
                    account={{
                        balance: user.account.balance,
                        earning: user.account.earning,
                        bonus: user.account.bonus,
                    }}
                    user_id={user.id}
                />
                <Earning earning={user.earning} user_id={user.id} />
                <Investment investment={user.investment} user_id={user.id} />
                <Notification
                    notification={user.notifications}
                    user_id={user.id}
                    apiToken={apiToken}
                />
            </div>
        </AuthenticatedLayout>
    );
}
