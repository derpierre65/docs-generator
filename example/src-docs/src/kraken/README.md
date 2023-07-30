---
sidebar: [".\/README.md"]
sidebarDepth: 2
---

# Kraken Resources

| Resource | Endpoint | Description |
|----------|----------|-------------|
| User | [Get Users](.//README.md#get-users) | Get list of Users @index |
| User | [Get Users](.//README.md#get-users-1) | Get list of Users @index2 |
| User | [Get Users](.//README.md#get-users-2) | Get list of Users |
| My Test | [Get Test](.//README.md#get-test) | Hihi not a user resource in my user controller |

<docs-Endpoint>

## User

</docs-Endpoint>

<docs-Endpoint>

### Get Users

<docs-ApiUrl type="GET" endpoint="https://api.example.org/kraken/users" />

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

<docs-ApiUrl type="GET" endpoint="https://api.example.org/kraken/users" />

Get list of Users @index2

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

<docs-ApiUrl type="GET" endpoint="https://api.example.org/kraken/users" />

Get list of Users

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| a | Object | No description |
| &nbsp;&nbsp;&nbsp;b | Object | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c | Object | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d | Object | No description |
| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;enabled | Boolean | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "a": {
        "b": {
            "c": {
                "d": {
                    "enabled": true
                }
            }
        }
    }
}
```

</docs-EndpointExample>
</docs-Endpoint>

<docs-Endpoint>

## My Test

</docs-Endpoint>

<docs-Endpoint>

### Get Test

<docs-ApiUrl type="GET" endpoint="https://api.example.org/kraken/not-an-user" />

Hihi not a user resource in my user controller

#### Response Body

| Field | Type | Description |
|-------|------|-------------|
| id | Integer | No description |
| username | String | No description |
| email | String | No description |
| overlay_token | String | No description |
| created_at | Datetime | No description |
| just_an_object | Object | No description |
| &nbsp;&nbsp;&nbsp;datetime | Datetime | No description |
| &nbsp;&nbsp;&nbsp;my_string | String | No description |
| nested_object_array | Object[] | No description |
| &nbsp;&nbsp;&nbsp;datetime | Datetime | No description |
| &nbsp;&nbsp;&nbsp;my_string | String | No description |
| test | Test2 | No description |

<docs-EndpointExample>

#### Example response

```json
{
    "id": 42,
    "username": "derpierre65",
    "email": "hello@derpierre65.dev",
    "overlay_token": "pu72I3Vkz7iAJegTpzp28ctTWsm",
    "created_at": "2022-01-01T00:00:00Z",
    "just_an_object": {
        "datetime": "2022-01-01T00:00:00Z",
        "my_string": "hello world"
    },
    "nested_object_array": [
        {
            "datetime": "2022-01-01T00:00:00Z",
            "my_string": "hello world"
        }
    ],
    "test": {
        "id": 42,
        "username": "derpierre65",
        "email": "hello@derpierre65.dev",
        "overlay_token": "pu72I3Vkz7iAJegTpzp28ctTWsm",
        "created_at": "2022-01-01T00:00:00Z"
    }
}
```

</docs-EndpointExample>
</docs-Endpoint>