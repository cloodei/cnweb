# Travel Planner

Travel Planner is a shared trip-planning context. It distinguishes reusable destination catalog content from private group workspaces where members coordinate itineraries.

## Language

**Group**:
A private trip-planning workspace with members and roles. Groups own itineraries and are the boundary for viewing or changing trip plans.
_Avoid_: Public share, category, admin area

**Group member**:
A user who belongs to a group with a role of owner, editor, or viewer.
_Avoid_: Public viewer, anonymous collaborator

**Group owner**:
A group member responsible for group settings and invite links.
_Avoid_: Admin, creator

**Group invite**:
A time-limited and use-limited link that lets an authenticated user join a group with a selected role.
_Avoid_: Share link, public itinerary link

**Itinerary**:
A trip plan inside one group, with dates, optional primary destination, description, and scheduled stops.
_Avoid_: Personal schedule, public share

**Scheduled stop**:
The group itinerary's planned visit to a shared location or private group destination, including visit time and trip-specific notes.
_Avoid_: Location, group destination

**Location**:
A reusable shared destination-catalog entry that can be scheduled in group itineraries and may store map-provider place data.
_Avoid_: Stop, private group destination

**Group destination**:
A private destination saved inside one group for quick itinerary planning.
_Avoid_: Shared location, scheduled stop

**Admin**:
A system moderation role separate from group membership and group ownership.
_Avoid_: Group owner, collaborator
