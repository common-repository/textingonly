/**
 * External dependencies.
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { showPromiseToast } from '../../../utils';
import { fetchOptions, saveOptions } from '../../../api/settings';
import SettingsLayout from '../../layout/SettingsLayout';
import { Link } from "react-router-dom";
import { SettingsCard, DivCard, RadioSelectInput, MultiSelectInput, SelectInput, TextInput, ToggleInput, PasswordInput, ColorPickerInput } from '../../templates';

const GeneralSettings = () => {

    const [processing, setProcessing] = useState(true);

    const [options, setOptions] = useState({
        "to-account-email": "",
        "to-api-key": "",
        "to-qrcids": "",
        "to-plan": "",
        "default-qrcid": "",
        "default-style": "default",
        "default-btn-txt": "",
        "default-bg-color": "#003366",
        "default-txt-color": "#e7e8e9",
        "default-font-size": "reg",
        "default-icon": "no",
        "sitewide-display": "no",
        "sitewide-where": "all",
    });

    const updateOption = ( value, id ) => {
        setOptions({...options, [id]: value });
    }

    const onSave = () => {
        if ( !processing ) {
            const res = saveOptions( { options } );
            showPromiseToast( res, '', 'Settings updated!' );
        }
    }

    useEffect( () => {
        const updateOptions = ( settings ) => setOptions({ ...options, ...settings });
        const res = fetchOptions( { updateOptions } ).then( res => setProcessing(false) );
        showPromiseToast( res );
    }, []);

    useEffect( () => {
        toSetUpColorPickers();
    }, []);

    const qrcsArr = options["to-qrcids"];
    const qrcOptions = [];
    { qrcsArr && qrcsArr.map( (qrc) => 
        qrcOptions[ qrc.code ] = qrc.name,
        )
    };

    const styleOptions = [];
    styleOptions['default'] = 'Default';
    styleOptions['rounded'] = 'Rounded';

    const fsizeOptions = [];
    fsizeOptions['reg'] = 'Regular';
    fsizeOptions['sm'] = 'Small';
    fsizeOptions['lg'] = 'Large';
    fsizeOptions['xl'] = 'XL';

    const iconOptions = [];
    iconOptions['no'] = 'No - None';
    iconOptions['light'] = 'Yes - White Icon';
    iconOptions['dark'] = 'Yes - Black Icon';

    const sitewideOptions = [];
    sitewideOptions['no'] = 'No - Do Not Display';
    sitewideOptions['yes--bot-right'] = 'Yes - Display on bottom right';
    sitewideOptions['yes--bot-left'] = 'Yes - Display on bottom left';
    sitewideOptions['yes--bot-center'] = 'Yes - Display on bottom centered';
    sitewideOptions['yes--mid-right'] = 'Yes - Display on middle right';
    sitewideOptions['yes--mid-left'] = 'Yes - Display on middle left';
    sitewideOptions['yes--top-right'] = 'Yes - Display on top right';
    sitewideOptions['yes--top-left'] = 'Yes - Display on top left';
    sitewideOptions['yes--top-center'] = 'Yes - Display on top centered';

    const fwhereOptions = [];
    fwhereOptions['all'] = 'All devices / Screen sizes';
    fwhereOptions['no-mobile'] = 'Only on Desktop';
    fwhereOptions['no-desktop'] = 'Only on Mobile';

    const showRegisterBtn = ( options["to-account-email"] == '' || options["to-api-key"] == '' ) ? true : false;
    const showSyncBtn = ( options["to-account-email"] == '' || options["to-api-key"] == '' ) ? false : true;

    return (
        <SettingsLayout>
            { showRegisterBtn &&
            <DivCard
                title="Need Help With Setup?"
                description="View our detailed user guide for information and tips on setting up the plugin."
                userguide={true}
                showbtn={false}
            >
            </DivCard>
            }
            <SettingsCard
                title="TextingOnly Account"
                description="Enter basic details about your TextingOnly account: email and API key."
                linktext=""
                linkurl=""
                onSave={onSave}
            >
            { showRegisterBtn &&
                <Link 
                    to="https://www.textingonly.com/account/register-free" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="button button-primary py-1 px-3"
                >
                    Register for free account and API key
                </Link>
            }
                <TextInput
                    id="to-account-email"
                    label="TextingOnly Account Email"
                    description="The email address associated with your TextingOnly account."
                    type="email"
                    value={options["to-account-email"]}
                    placeholder="your@email.com"
                    setOption={updateOption}
                />
                
                <PasswordInput
                    id="to-api-key"
                    label="TextingOnly API Key"
                    description="Get your API key in your"
                    type="number"
                    value={options["to-api-key"]}
                    placeholder=""
                    linktext="TextingOnly account dashboard"
                    linkurl="https://www.textingonly.com/apikey"
                    setOption={updateOption}
                />
            </SettingsCard>

            <DivCard
                title="Available Links / Buttons"
                description="These are the available links/buttons from your TextingOnly account. Sync accounts here whenever you make updates within your TextingOnly account."
                userguide={false}
                showbtn={showSyncBtn}
            >

            { options["to-plan"] &&

                <h4><a href="https://www.textingonly.com/dashboard" class="hover:underline" target="_blank" rel="noopener">Current TextingOnly Plan</a>: <a href="https://www.textingonly.com/dashboard" class="hover:underline" target="_blank" rel="noopener"><strong>{options["to-plan"]} Plan</strong></a></h4>
            }

            { ( ! options["to-qrcids"] || options["to-qrcids"] == '' ) ?
               <span class="text-gray-400 block py-2">No options to choose from.</span>
            :
                <table class="w-full text-sm text-left rtl:text-right text-gray-800 dark:text-gray-800">
                <thead class="text-xs text-gray-200 uppercase bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Link</th>
                <th scope="col" class="px-6 py-3">Shortcode</th>
                </thead>
                <tbody class="bg-white border-b dark:bg-gray-300 dark:border-gray-400">
                {qrcsArr && qrcsArr.map( (qrc) => 
                    <tr>
                        <td class="px-6 py-3">{qrc.name}</td>
                        <td class="px-6 py-3">https://c.txtng.co/{qrc.code}</td>
                        <td class="px-6 py-3">[textingonly code="{qrc.code}"]</td>
                    </tr>
                    )
                }
                </tbody>
                </table>
            }

                <span class="pyy-0" id="text-us-now-response" role="alert"></span>

            </DivCard>

            <SettingsCard
                title="Default Settings"
                description="Default options and display settings. These define how your button will look and act when the default shortcode [textingonly] is used."
                onSave={onSave}
            >
            { ( ! options["to-qrcids"] || options["to-qrcids"] == '' ) ?
                
                <span>
                    <p><strong>Default Link/Button</strong></p>
                    <span class="text-gray-400 block py-4">No options to choose from.</span>
                </span>
            :
                <SelectInput
                    id="default-qrcid"
                    label="Default Link/Button"
                    description="Select which available link/button to set as default."
                    value={options["default-qrcid"]}
                    options={qrcOptions}
                    setOption={updateOption}
                />
            }

                <TextInput
                    id="default-btn-txt"
                    label="Default Button Text"
                    description="The default text that links/buttons will display."
                    type="text-us-now-response"
                    value={options["default-btn-txt"]}
                    placeholder="Text Us"
                    setOption={updateOption}
                />

                <SelectInput
                    id="default-style"
                    label="Default Button Style"
                    description="Choose which style to display the link(s)/button(s)."
                    value={options["default-style"]}
                    options={styleOptions}
                    setOption={updateOption}
                />

                <SelectInput
                    id="default-font-size"
                    label="Font Size"
                    description="Font size of button/link text."
                    value={options["default-font-size"]}
                    options={fsizeOptions}
                    setOption={updateOption}
                />

                <ColorPickerInput
                    id="default-bg-color"
                    label="Default Button Background Color"
                    //defaultColor="#010101"
                    className="to--colorpicker"
                    description="Select a background color to set as default."
                    value={options["default-bg-color"]}
                    setOption={updateOption}
                />

                <ColorPickerInput
                    id="default-txt-color"
                    label="Default Button Text Color"
                    //defaultColor="#f7f8f9"
                    className="to--colorpicker"
                    description="Select a background color to set as default."
                    value={options["default-txt-color"]}
                    setOption={updateOption}
                />

                <SelectInput
                    id="default-icon"
                    label="Display Phone Icon in Links?"
                    description="Select whether or not to display a phone icon with your links/buttons."
                    value={options["default-icon"]}
                    options={iconOptions}
                    setOption={updateOption}
                />

            </SettingsCard>
            <SettingsCard
                title="Sitewide Settings"
                description="Choose whether or not to display a site-wide link/button. Note that the link/button used here will be defined by the defaults set above in the 'Default Settings' section. If a default link/button is not set above, nothing will display."
                onSave={onSave}
            >
                <SelectInput
                    id="sitewide-display"
                    label="Sitewide Link/Button"
                    description="Select whether to display a sitewide (floating) link/button. And, if so, where."
                    value={options["sitewide-display"]}
                    options={sitewideOptions}
                    setOption={updateOption}
                />
                <SelectInput
                    id="sitewide-where"
                    label="What Devices to Display on?"
                    description="Select whether the sitewide link/button should display on desktop, mobile or all devices."
                    value={options["sitewide-where"]}
                    options={fwhereOptions}
                    setOption={updateOption}
                />
            </SettingsCard>
            { ! showRegisterBtn &&
            <DivCard
                title="Need Help With Setup?"
                description="View our detailed user guide for information and tips on setting up the plugin."
                userguide={true}
                showbtn={false}
            >
            </DivCard>
            }
        </SettingsLayout>
    )
}

export default GeneralSettings;