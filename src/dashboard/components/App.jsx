/**
 * External dependencies.
 */
import { Route, Routes, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';

/**
 * Internal dependencies.
 */
import { Dashboard, Changelog } from './pages';
import { GeneralSettings } from './pages/settings';

const App = () => {
    return (
        <>
            <Toaster position="bottom-center" />
            <Routes>
                <Route path="/about" element={<Dashboard />} />
                <Route path="/changelog" element={<Changelog />} />
                <Route path="/settings" element={<GeneralSettings />} />

                {/* When no routes match, it will redirect to this route path. Note that it should be registered above. */}
                <Route
                    path="*"
                    element={<Navigate to="/dashboard" replace />}
                />
            </Routes>
        </>
    )
}

export default App;