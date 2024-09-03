import {call as fetchMany} from 'core/ajax';

/**
 * Get all converstations a User can see.
 * @param {int} id
 * @returns {mixed}
 */
export const deleteSource = (
    id,
) => fetchMany([{
    methodname: 'repository_imagehub_delete_source',
    args: {
        id,
}}])[0];
