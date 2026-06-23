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
A trip plan inside one group, with dates, description, and scheduled stops.
_Avoid_: Personal schedule, public share

**Scheduled stop**:
The group itinerary's planned visit to a catalog location, including visit time and trip-specific notes.
_Avoid_: Location

**Location**:
A reusable destination-catalog entry that can be scheduled in group itineraries.
_Avoid_: Stop, private destination

**Admin**:
A system moderation role separate from group membership and group ownership.
_Avoid_: Group owner, collaborator
