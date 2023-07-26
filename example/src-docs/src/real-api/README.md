---
sidebar: ['./README.md']
sidebarDepth: 2
---
# Resources

| Resource | Endpoint | Description |
|----------|----------|-------------|
| User | [Get Users](.//README.md#get-users) | Gets a list of all users. |
| User | [Update user](.//README.md#update-user) | Updates the user’s information. |


<docs-Endpoint>

# User

</docs-Endpoint>

<docs-Endpoint>

## Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/v1/users" />

Gets a list of all users.



### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| slug | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| avatar | String | No description |
| acting_as | User | No description |
| scopes | String[] | No description |
| created_at | Datetime | No description |



<docs-EndpointExample>

### Code samples

```js
// code example soon
```
</docs-EndpointExample>
</docs-Endpoint><docs-Endpoint>

## Update user

<docs-ApiUrl type="PATCH" endpoint="https://api.example.org/v1/users/{user}" />

Updates the user’s information.



### Response Body

| Field | Type | Description |
|-------|------|-------------|
| data | User | No description |



<docs-EndpointExample>

### Code samples

```js
// code example soon
```
</docs-EndpointExample>
</docs-Endpoint>