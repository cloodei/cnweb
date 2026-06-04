# Product Intent

## Purpose

Travel Planner is intended to become a shared planning space for trips. A group should be able to collect destinations, decide where to go, arrange a schedule, and keep the current plan visible to everyone involved.

The existing codebase implements the first layer of that idea:

1. A shared catalog of destination information.
2. A personal itinerary builder.
3. Read-only public sharing for an itinerary.
4. Basic moderation for administrators.

It does not yet implement a true group workspace.

## Domain Language

Use these terms consistently in code and documentation:

| Term | Meaning |
| --- | --- |
| Category | A way to group catalog locations, such as beaches, mountains, or museums. |
| Location | A reusable destination-catalog entry with a name, category, description, address, and optional image. |
| Itinerary | A trip plan with a title, dates, description, and an ordered-by-time list of scheduled locations. |
| Scheduled stop | The relationship between an itinerary and a location. It carries the visit time and trip-specific note. |
| Share link | A public, read-only itinerary page. It is not an invitation and does not grant edit access. |
| Admin | A user with moderation access for categories, users, and itineraries. |

## Current Product Surface

| Capability | Status | Notes |
| --- | --- | --- |
| Browse destinations | Implemented | Signed-in users can browse locations and categories. |
| Contribute destinations | Implemented | Any signed-in user can add a location. The contributor or an admin can edit or delete it. |
| Plan a trip | Implemented | An itinerary belongs to exactly one user. |
| Schedule stops | Implemented | An itinerary owner can attach locations with visit times and notes. |
| Export a trip | Implemented | An itinerary owner can download a PDF. |
| Share a trip | Implemented, limited | Anyone with `/shared/itineraries/{id}` can read the itinerary. |
| Moderate content | Partially implemented | Admin pages list users and itineraries and allow deletion. Category administration has route and UI inconsistencies. |
| Group membership | Not implemented | There are no group, participant, membership, or invitation tables. |
| Collaborative editing | Not implemented | Only the itinerary owner can change an itinerary. |
| Coordination tracking | Not implemented | There are no comments, votes, tasks, decisions, or activity records. |

## Target Collaboration Model

The smallest useful next step is itinerary-level collaboration. Keep `itineraries` as the trip workspace and add participants directly to each itinerary:

| Proposed concept | Responsibility |
| --- | --- |
| `itinerary_user` membership | Connect users to a trip with roles such as owner, editor, and viewer. |
| Itinerary invitation | Invite a user or email address to join a trip without exposing edit access through a public URL. |
| Membership-aware authorization | Replace owner-only checks with policies that distinguish owner, editor, viewer, admin, and anonymous share-link access. |
| Activity record | Capture important changes when the group needs traceability. |

Add a separate reusable `groups` table only when the product needs a stable group that owns multiple trips. Do not introduce that extra abstraction merely to enable several people to edit one itinerary.

## Product Rules

- Locations are shared catalog content, not private itinerary data.
- A scheduled stop is trip-specific. Its visit time and note belong on the itinerary-location relation.
- A public share URL is read-only.
- Edit access must come from authenticated membership or admin status, never from possession of a public share URL.
- Admin capabilities are moderation tools. They are not a substitute for group ownership or collaboration roles.
- If sensitive itinerary data is added, replace predictable public IDs with revocable share tokens or signed URLs.

## Out Of Scope Today

These may be useful later, but they are not represented in the current implementation:

- Reusable travel groups.
- Invitations and membership lifecycle.
- Collaborative comments, voting, checklists, or expense splitting.
- Notifications and real-time updates.
- Geospatial search or route optimization.
- API clients or a mobile application.
