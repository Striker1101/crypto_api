import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";
import { ToastContainer } from "react-toastify";
import { Bounce } from "react-toastify";
import CoinSVG from "../Components/CoinSVG";
import "../../css/coin.css";
import "react-toastify/dist/ReactToastify.css";
export default function Guest({ children }) {
    const coins = Array.from({ length: 20 }, (_, i) => {
        const rotateSpeed = (2 + Math.random() * 3).toFixed(2);
        const fallSpeed = (3 + Math.random() * 3).toFixed(2);
        const delay = (Math.random() * 5).toFixed(2);
        const left = Math.random() * 100;

        return (
            <div
                key={i}
                className="coin-wrapper absolute"
                style={{
                    left: `${left}%`,
                    animation: `fall ${fallSpeed}s linear ${delay}s infinite`,
                }}
            >
                <div
                    className="coin-spin"
                    style={{
                        animation: `spin ${rotateSpeed}s linear infinite`,
                    }}
                >
                    <CoinSVG />
                </div>
            </div>
        );
    });
    return (
        <>
            <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-300 relative overflow-hidden">
                <div className="coin-container">{coins}</div>
                <ToastContainer
                    position="top-right"
                    autoClose={5000}
                    hideProgressBar={false}
                    newestOnTop={false}
                    closeOnClick={false}
                    rtl={false}
                    pauseOnFocusLoss
                    draggable
                    pauseOnHover
                    theme="light"
                    transition={Bounce}
                />
                <div className="z-10">
                    <Link href="/">
                        <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
                    </Link>
                </div>

                <div className="z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {children}
                </div>
            </div>
        </>
    );
}
