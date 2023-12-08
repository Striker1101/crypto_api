// resources/js/Components/Pagination.jsx

import React from "react";
import { InertiaLink } from "@inertiajs/inertia-react";

const Pagination = ({ data }) => {
    const { current_page, last_page, path } = data;

    return (
        <div className="flex justify-between items-center mt-4 mx-5 my-5 ">
            <div>
                {current_page > 1 && (
                    <InertiaLink
                        href={`${path}?page=${current_page - 1}`}
                        className="text-blue-600 hover:underline"
                    >
                        Previous
                    </InertiaLink>
                )}
            </div>

            <div>
                Page {current_page} of {last_page}
            </div>

            <div>
                {current_page < last_page && (
                    <InertiaLink
                        href={`${path}?page=${current_page + 1}`}
                        className="text-blue-600 hover:underline"
                    >
                        Next
                    </InertiaLink>
                )}
            </div>
        </div>
    );
};

export default Pagination;
