import { ArrowUpIcon, CheckIcon, CheckCircleIcon, StarIcon, ChatBubbleLeftRightIcon, CubeIcon, LockClosedIcon, LockOpenIcon, SparklesIcon, SquaresPlusIcon } from '@heroicons/react/20/solid';
import { Link } from "react-router-dom";
import Layout from '../layout/Layout';
import {Cog6ToothIcon} from "@heroicons/react/20/solid";
import {InformationCircleIcon} from "@heroicons/react/20/solid";
import {DocumentArrowUpIcon} from "@heroicons/react/20/solid";

const features = [
    {
        name: 'Free Version of this Plugin',
        description: 'The free, basic version of this plugin allows you to use one of your own SMS numbers to communicate directly with website users.',
        icon: CheckIcon,
    },
    {
        name: 'Unlock Powerful Texting Tools',
        description: 'Interact and engage with current users and prospective new clients via text. Expand your reach to new demographics.',
        icon: LockOpenIcon,
    },
    {
        name: 'Upgrade for Advanced Features',
        description: 'Upgrade your plan and take advantage of TextingOnly\'s advanced SMS features like automated conversations and replies.',
        icon: ArrowUpIcon,
    },
];
const features2 = [
    {
        name: 'Improved Communication',
        description: 'No matter what the purpose, SMS is an effective way to communicate. Improve communication and increase engagement.',
        icon: SparklesIcon,
    },
    {
        name: 'Automated Text Messaging',
        description:
            'Discover & qualify new leads easily via text conversations. Collect feedback from clients or customers seamlessly with SMS.',
        icon: ChatBubbleLeftRightIcon,
    },
    {
        name: 'SMS Lead Generation',
        description: 'Our customizable, conversational SMS system used to ask questions inside of the SMS framework on the user\'s phone.',
        icon: StarIcon,
    },
];
const Dashboard = () => {
    return (
        <Layout>
            <div className="overflow-hidden bg-white py-10 rounded">
                <div className="mx-auto max-w-7xl px-6 lg:px-8">
                    <div className="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none">
                        <div className="lg:pr-8">
                            <p className="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">About TextingOnly</p>
                            <h2 className="text-base font-semibold leading-7 text-gray-600">Business Text Messaging</h2>
                            <p className="mt-4 text-lg leading-7 text-gray-600">
                                Improve lead quality and increase conversion with powerful SMS marketing tools. Modernize communication with customers and prospects through automation and tracking.
                            </p>
                            <Link 
                                to="https://www.textingonly.com/wordpress-plugin?utm_source=wppd"
                                class="button button-primary dash-btn outline-0 shadow-none mt-6 mb-6 mr-3 pt-1 pb-1"
                                target="blank"
                            >
                                <InformationCircleIcon className="h-5 w-5 mr-1.5 mb-1 inline-block align-middle" />
                                User Guide / Read Me
                            </Link>
                            <Link 
                                to="/settings"
                                class="button button-primary dash-btn outline-0 shadow-none mt-6 mb-6 pt-1 pb-1"
                            >
                                <Cog6ToothIcon className="h-5 w-5 mr-1.5 mb-1 inline-block align-middle" />
                                Plugin Settings
                            </Link>
                            <h3 className="mt-6 text-xl font-semibold leading-5 text-gray-900">Free Plugin</h3>
                            <dl className="mt-6 mb-6 max-w-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16 text-base leading-7 text-gray-600 lg:max-w-none">
                                {features.map((feature) => (
                                    <div key={feature.name} className="relative pl-9">
                                        <dt className="inline font-semibold text-gray-900">
                                            <feature.icon className="absolute left-1 top-1 h-5 w-5 text-gray-700" aria-hidden="true" />
                                            {feature.name}
                                        </dt>{' '}
                                        <dd className="inline-block">{feature.description}</dd>
                                    </div>
                                ))}
                            </dl>
                            <h3 className="mt-12 text-xl font-semibold leading-5 text-gray-900">Upgraded Plans</h3>
                            <dl className="mt-6 mb-6 max-w-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16 text-base leading-7 text-gray-600 lg:max-w-none">
                                {features2.map((feature) => (
                                    <div key={feature.name} className="relative pl-9">
                                        <dt className="inline font-semibold text-gray-900">
                                            <feature.icon className="absolute left-1 top-1 h-5 w-5 text-gray-700" aria-hidden="true" />
                                            {feature.name}
                                        </dt>{' '}
                                        <dd className="inline-block">{feature.description}</dd>
                                    </div>
                                ))}
                            </dl>
                        </div>
                    </div>
                    <Link 
                        to="https://www.textingonly.com?utm_source=wppd"
                        class="button button-primary dash-btn outline-0 shadow-none mt-6 mb-6 pt-1 pb-1"
                        target="blank"
                    >
                        <DocumentArrowUpIcon className="h-5 w-5 mr-1.5 mb-1 inline-block align-middle" />
                        More About TextingOnly
                    </Link>
                </div>
            </div>
        </Layout>
    );
}

export default Dashboard;