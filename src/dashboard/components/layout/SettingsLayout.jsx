/**
 * External dependencies.
 */
import { Route, Routes } from 'react-router-dom';
import {Cog6ToothIcon} from "@heroicons/react/20/solid";
import { HomeIcon } from '@heroicons/react/20/solid';

/**
 * Internal dependencies.
 */
import { Header, Footer, Content } from '../parts';

export default function SettingsLayout({ children }) {
    const navigation = [
        { name: 'About', href: '/about', icon: HomeIcon },
    ]

    const secondaryNav = [
        { name: 'Settings', href: '/settings', icon: Cog6ToothIcon },
    ]

    return (
        <>
            <div className="min-h-full">
                <Header navigation={navigation} secondaryNav={secondaryNav} />
                <Content className="space-y-10">{children}</Content>
                <Footer />
            </div>
        </>
    )
}