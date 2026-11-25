<?php

namespace SaltLaravel\Support;

/**
 * Which part of a request's path names the collection a resource controller serves.
 *
 * A resource route is `<prefix>/{collection}`, `<prefix>/{collection}/{id}`, or one of those followed by a fixed
 * word: `{collection}/trash`, `{collection}/import`, ... and, on a record, `{collection}/{id}/trashed`,
 * `{collection}/{id}/restore` and `{collection}/{id}/delete`, where `{id}` may also be `selected` or `all`.
 *
 * The collection is read from the route's `{collection}` parameter when the route has one (the generic routes).
 * Otherwise (a package's own routes, such as `categories/{id}/restore`) it is read from the path: a trailing action
 * word is set aside, then the collection is the last segment when there is an odd number of segments (no id after
 * it) and the one before the last when there is an even number (an id after it). A collection may therefore not be
 * named `trashed`, `restore` or `delete`.
 */
final class ResourceSegment
{
    /** The words a route puts after a record's id (or `selected` / `all`) to name what it does to it. */
    public const ACTION_WORDS = ['trashed', 'restore', 'delete'];

    /**
     * @param  array<int, string>  $segments  the request's path segments, in order
     * @param  mixed  $routeCollection  the route's `{collection}` parameter, when it has one
     */
    public static function collection(array $segments, $routeCollection = null): ?string
    {
        if (is_string($routeCollection) && $routeCollection !== '') {
            return $routeCollection;
        }

        $segments = array_values($segments);
        if (count($segments) > 1 && in_array(end($segments), self::ACTION_WORDS, true)) {
            array_pop($segments);
        }

        $count = count($segments);
        if ($count === 0) {
            return null;
        }

        return $count % 2 === 0 ? $segments[$count - 2] : $segments[$count - 1];
    }
}
