/**
 * WordPress dependencies
 */
import { registerStore } from '@wordpress/data';

/**
 * Internal dependencies
 */
import controls from './controls';
import * as actions from './actions';
import * as selectors from './selectors';
import adminNotices from './reducers';
import * as resolvers from './resolvers';

const store = registerStore( 'ithemes-security/admin-notices', {
	controls,
	actions,
	selectors,
	resolvers,
	reducer: adminNotices,
} );

export default store;
