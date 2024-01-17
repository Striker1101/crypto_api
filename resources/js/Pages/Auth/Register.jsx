import { useEffect } from "react";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        street: "",
        city: "",
        state: "",
        zip_code: "",
        country: "",
        password: "",
        password_confirmation: "",
    });

    useEffect(() => {
        return () => {
            reset("password", "password_confirmation");
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();

        post(route("register"));
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="name" value="Name" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full"
                        autoComplete="name"
                        isFocused={true}
                        onChange={(e) => setData("name", e.target.value)}
                        required
                    />

                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        onChange={(e) => setData("email", e.target.value)}
                        required
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="street" value="Street" />

                    <TextInput
                        id="street"
                        name="street"
                        value={data.street}
                        className="mt-1 block w-full"
                        autoComplete="street"
                        isFocused={true}
                        onChange={(e) => setData("street", e.target.value)}
                        required
                    />

                    <InputError message={errors.street} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="city" value="City" />

                    <TextInput
                        id="city"
                        name="city"
                        value={data.city}
                        className="mt-1 block w-full"
                        autoComplete="city"
                        isFocused={true}
                        onChange={(e) => setData("city", e.target.value)}
                        required
                    />

                    <InputError message={errors.city} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="state" value="State" />

                    <TextInput
                        id="state"
                        name="state"
                        value={data.state}
                        className="mt-1 block w-full"
                        autoComplete="state"
                        isFocused={true}
                        onChange={(e) => setData("state", e.target.value)}
                        required
                    />

                    <InputError message={errors.state} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="zip_code" value="Zip Code" />

                    <TextInput
                        id="zip_code"
                        name="zip_code"
                        value={data.zip_code}
                        className="mt-1 block w-full"
                        autoComplete="zip_code"
                        isFocused={true}
                        onChange={(e) => setData("zip_code", e.target.value)}
                        required
                    />

                    <InputError message={errors.zip_code} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="country" value="Country" />

                    <TextInput
                        id="country"
                        name="country"
                        value={data.country}
                        className="mt-1 block w-full"
                        autoComplete="country"
                        isFocused={true}
                        onChange={(e) => setData("country", e.target.value)}
                        required
                    />

                    <InputError message={errors.country} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) => setData("password", e.target.value)}
                        required
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                        required
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>


                <div className="flex items-center justify-end mt-4">
                    <Link
                        href={route("login")}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Already registered?
                    </Link>

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
