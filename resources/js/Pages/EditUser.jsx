import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
export default function Dashboard({ auth, users }) {
    return (
        <div>
            <AuthenticatedLayout
                user={auth.user}
                header={
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Edit - {auth.user.name}
                    </h2>
                }
            ></AuthenticatedLayout>
        </div>
    );
}
