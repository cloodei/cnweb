# Product Intent

## Purpose

Travel Planner is a shared planning space for trips. A group can collect the trip plan, decide where to go, arrange a schedule, and keep the current plan visible to everyone involved.

The existing codebase implements:

1. A shared catalog of destination information.
2. Private group workspaces with owner, editor, and viewer roles.
3. Group invitation links with expiry and use limits.
4. Group itinerary planning.
5. Basic moderation for administrators.

## Domain Language

Use these terms consistently in code and documentation:

| Term | Meaning |
| --- | --- |
| Category | Internal catalog metadata used by admins to group locations. It is not a primary user-facing browse surface. |
| Location | A reusable destination-catalog entry with a name, description, address, optional image, and optional Google Maps place data. |
| Group | A private trip-planning workspace with members and roles. |
| Group destination | A private destination saved inside one group for quick itinerary planning. |
| Group member | A user who belongs to a group as owner, editor, or viewer. |
| Group invite | A time-limited and use-limited link that lets an authenticated user join a group. |
| Itinerary | A trip plan inside one group, with a title, dates, description, and an ordered-by-time list of scheduled locations. |
| Scheduled stop | The relationship between an itinerary and a location. It carries the visit time and trip-specific note. |
| Admin | A user with moderation access for categories, users, and itineraries. |

## Current Product Surface

| Capability | Status | Notes |
| --- | --- | --- |
| Browse destinations | Implemented | Signed-in users can browse and search locations. Category browsing redirects back to destinations. |
| Contribute destinations | Implemented | Any signed-in user can add a location manually or with Google Maps place selection. The contributor or an admin can edit or delete it. |
| Create groups | Implemented | A signed-in user can create a group and becomes its owner. |
| Invite group members | Implemented | Owners can create editor or viewer invite links with selectable duration and max uses. |
| Save group destinations | Implemented | Owners and editors can save private group destinations manually or with Google Maps place selection. |
| Plan a trip | Implemented | An itinerary belongs to exactly one group and is always accessed through that group. |
| Schedule stops | Implemented | Group owners and editors can attach shared locations or private group destinations with visit times and notes. |
| Export a trip | Implemented | Group members can download a PDF for itineraries they can view. |
| Moderate content | Partially implemented | Admins can create, rename, and safely delete unused categories; edit user names, emails, and roles; and list/delete itineraries. Admins do not delete user accounts. There is no audit trail, soft delete, or recovery flow. |
| Group membership | Implemented | Group membership uses owner, editor, and viewer roles. |
| Collaborative editing | Partially implemented | Owners and editors can change itineraries and scheduled stops; viewers can read. |
| Coordination tracking | Not implemented | There are no comments, votes, tasks, decisions, or activity records. |

## Collaboration Model

Groups are persistent planning workspaces. A group can own multiple itineraries, and users access itineraries through their group membership.

| Concept | Responsibility |
| --- | --- |
| Group membership | Connect users to a group with roles such as owner, editor, and viewer. |
| Group invitation | Invite a user to join a group without exposing itinerary data through a public URL. |
| Membership-aware authorization | Policies distinguish owner, editor, viewer, admin moderation, and guests. |
| Activity record | Capture important changes when the group needs traceability. |

## Product Rules

- Locations are shared catalog content, not private itinerary data.
- Group destinations are private to one group and should not appear in the shared destination catalog.
- Categories are admin/internal catalog metadata and should not be surfaced as required user-facing destination context.
- A scheduled stop is trip-specific. Its visit time and note belong on the itinerary-location relation.
- Itineraries belong to groups, not directly to one user's private workspace.
- View and edit access must come from authenticated group membership.
- Group invite links join users to groups. They are not public itinerary pages.
- Admin capabilities are moderation tools. They are not a substitute for group ownership or collaboration roles.
- Admin moderation does not make an admin a group owner.

## Out Of Scope Today

These may be useful later, but they are not represented in the current implementation:

- Collaborative comments, voting, checklists, or expense splitting.
- Notifications and real-time updates.
- Geospatial search or route optimization.
- API clients or a mobile application.
