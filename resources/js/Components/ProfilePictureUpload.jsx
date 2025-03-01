import { useState, useEffect } from "react";

const ProfilePictureUpload = ({ profilePicture, onChange }) => {
    const [preview, setPreview] = useState(profilePicture || null);

    // Update preview when profilePicture changes from backend
    useEffect(() => {
        if (profilePicture) {
            setPreview(profilePicture);
        }
    }, []);

    const handleImageChange = (e) => {
        const file = e.target.files[0];

        if (file) {
            const imageUrl = URL.createObjectURL(file); // ✅ Temporary preview URL
            setPreview(imageUrl); // ✅ Set preview correctly
            onChange(file); // ✅ Pass file to parent
        }
    };

    return (
        <div className="mb-4">
            <label
                htmlFor="profile_picture"
                className="block text-sm font-medium text-gray-600"
            >
                Profile Picture
            </label>

            {/* Image Preview */}
            {preview && (
                <div className="mb-2">
                    <img
                        src={typeof preview === "string" ? preview : ""}
                        alt="Profile Preview"
                        className="rounded-lg w-32 h-32 object-cover border"
                    />
                </div>
            )}

            {/* File Input */}
            <input
                type="file"
                id="profile_picture"
                name="profile_picture"
                accept="image/*"
                onChange={handleImageChange}
                className="mt-1 p-2 w-full border rounded-md"
            />
        </div>
    );
};

export default ProfilePictureUpload;
