import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ListUsers from "@/Components/ListUsers";
import { InertiaLink } from "@inertiajs/inertia-react";
import Pagination from "@/Components/Pagination";
export default function Dashboard({ auth, users }) {
    return (
        <div>
            <AuthenticatedLayout
                user={auth.user}
                header={
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard
                    </h2>
                }
            >
                <Head title="Dashboard" />
                <button className=" m-4  px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                    <InertiaLink href={`/dashboard/plan/edit`} method="get">
                        Edit Plans Here
                    </InertiaLink>
                </button>
                <ListUsers users={users.data} />
                <Pagination data={users} />
            </AuthenticatedLayout>
        </div>
    );
}
