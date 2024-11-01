/**
 * External dependencies.
 */
import clsx from 'clsx';

const PasswordInput = ({ id, type = 'password', label, description, linkurl, linktext, value, className, options, setOption, ...props }) => {

    const handleChange = e => {
        setOption( e.target.value, id );
    };

  const [inputType, setInputType] = React.useState("password");

  const buttonText = inputType === "password" ? "Show" : "Hide";

  const handleToggle = () => {
    setInputType((prevInputType) =>
      prevInputType === "password" ? "text" : "password"
    );
  }

    return (
        <fieldset>
            <legend className="text-sm font-semibold leading-6 text-gray-900">{label && label}</legend>
            <p className="mt-1 text-sm leading-6 text-gray-600">{description && description} <a href={linkurl} target="_blank" rel="noopener"><u>{linktext}</u></a></p>
            <input
                type={inputType}
                name={id}
                id={id}
                autoComplete="new-password"
                className={clsx(
                    "block w-full max-w-[320px] rounded-md border-0 mt-3 px-2.5 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6",
                           className && className
                )}
                onChange={handleChange}
                value={value}
                {...props}
            />
            { (value != '') ?
              <a
                onClick={handleToggle}
                class="text-xs rounded bg-gray-300 font-medium inline-block h-6 mt-1 py-1 px-2 border border-transparent focus:outline-none"
              >
                {buttonText}
              </a>
              : ''
            }
        </fieldset>

    );
}

export default PasswordInput;