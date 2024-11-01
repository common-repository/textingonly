import { Link } from "react-router-dom";
const DivCard = ({ title, description, children, linktext, linkurl, userguide, showbtn }) => {

    const handleOnUpdate = (e) => {
        e.preventDefault();
    }

    return (
        <div
            className="bg-white rounded"
        >
            <div className="px-6 py-4 border-b border-b-gray-200 flex justify-between">
                <div>
                    <h2 className="text-xl font-semibold leading-7 text-gray-900">{title && title}</h2>
                    <p className="mt-1 text-sm leading-6 text-gray-600">
                        {description && description} { (linkurl) ?
                            <a href={linkurl} target="_blank" rel="noopener"><u>{linktext}</u></a>
                            : ''
                        }
                    </p>
                </div>
            </div>
            <div className="p-6 space-y-5">
                {children}
                { userguide &&
                <Link 
                    to="https://www.textingonly.com/wordpress-plugin?utm_source=wpps" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="button button-primary dash-btn outline-0 shadow-none py-1 px-3"
                >
                    View the User Guide
                </Link>
            }
            </div>
        { showbtn &&
            <div className="px-6 py-4 border-t border-t-gray-200">
                <button
                    type="submit"
                    id="to-update-qrcs"
                    onClick={handleOnUpdate}
                    className="rounded-md bg-gray-700 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"

                >
                    Sync Account Details
                </button>
            </div>
        }
        </div>
    );
}

export default DivCard;