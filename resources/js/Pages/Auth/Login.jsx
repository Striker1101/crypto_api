import { useEffect, useState } from "react";
import Checkbox from "@/Components/Checkbox";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: false,
    });
    const [modalMessage, setModalMessage] = useState("");

    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const submit = async (e) => {
        e.preventDefault();
        const { email, password } = data;

        try {
            const response = await axios.post(route("login"), {
                email,
                password,
            });

            // Access the token from the response if needed
            const token = response.data.token;
            console.log("token", response);
            // Save the token to localStorage
            localStorage.setItem("token", token);

            // console.log(response.data?.users?.data[0]?.type);
            //reject login  when user is not admin
            setTimeout(() => {
                window.location.href = route("admin");
            }, 1000);
            // if (response.data?.users?.data[0]?.type == "admin") {
            //     // Redirect to the admin URL
            //     window.location.href = route("admin"); // Replace with your actual admin route
            // } else {
            //     alert("Only Admin can login here");
            // }
        } catch (error) {
            // Access error details if available
            const errorData = error.response?.data;
            const errorMessage = errorData?.message || "An error occurred";

            // Handle specific error cases
            if (error.response?.status === 422) {
                // Validation error
                const validationErrors = errorData.errors;
                setModalMessage(validationErrors.email);
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            } else {
                // Other types of errors
                console.error(errorMessage);
                setModalMessage(errorMessage);
                setTimeout(() => {
                    setModalMessage("");
                }, 2000);
            }
        }
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && (
                <div className="mb-4 font-medium text-sm text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData("email", e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={(e) => setData("password", e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData("remember", e.target.checked)
                            }
                        />
                        <span className="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <Link
                            href={route("password.request")}
                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Forgot your password?
                        </Link>
                    )}

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Log in
                    </PrimaryButton>
                </div>
            </form>

            {modalMessage && (
                <div
                    className={`fixed bottom-0 right-0 p-4 ${"bg-red-500"} text-white`}
                >
                    {modalMessage}
                </div>
            )}
        </GuestLayout>
    );
}
