// DashboardComponent.jsx

import React, { useEffect, useState } from "react";

const ListUsers = ({ users }) => {
    console.log(users);
    return (
        <div>
            <h1>User Details</h1>
            <ul>
                {users.map((user) => (
                    <li key={user.id}>
                        {user.name} - {user.email}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default ListUsers;
