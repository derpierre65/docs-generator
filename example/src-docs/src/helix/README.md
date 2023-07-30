---
sidebar: [".\/README.md"]
sidebarDepth: 2
---

# Helix Resources

| Resource | Endpoint | Description |
|----------|----------|-------------|
| User | [Get Users](.//README.md#get-users) | Get list of Users @index |
| User | [Get Users](.//README.md#get-users-1) | Get list of Users with social connections @index2 |
| User | [Get Users](.//README.md#get-users-2) | Get list of Users |

<docs-Endpoint>

## User

</docs-Endpoint>

<docs-Endpoint>

### Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/helix/users" />

Get list of Users @index

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| created_at | Datetime | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "id": 42,
    "username": "derpierre65",
    "email": "hello@derpierre65.dev",
    "overlay_token": "pu72I3Vkz7iAJegTpzp28ctTWsm",
    "created_at": "2022-01-01T00:00:00Z"
}
```

</docs-EndpointExample>
</docs-Endpoint>
<docs-Endpoint>

### Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/helix/users" />

Get list of Users with social connections @index2

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| data | Test[] | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "data": [
        {
            "id": 42,
            "username": "derpierre65",
            "email": "hello@derpierre65.dev",
            "overlay_token": "pu72I3Vkz7iAJegTpzp28ctTWsm",
            "created_at": "2022-01-01T00:00:00Z"
        }
    ]
}
```

</docs-EndpointExample>
</docs-Endpoint>
<docs-Endpoint>

### Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/helix/users" />

Get list of Users

<docs-EndpointExample>

#### Example response

```json
[]
```

</docs-EndpointExample>
</docs-Endpoint>