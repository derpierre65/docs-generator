<docs-Endpoint>
<template #header>

%endpoint_header_level% %endpoint_title%

</template>

<docs-ApiUrl type="%endpoint_method%" endpoint="%endpoint_url%" />

%endpoint_summary%
%endpoint_authorization%
%query_parameters%
%body_parameters%
%response_body%
%response_codes%

<template #example>
<docs-EndpointExample>

%endpoint_sub_header_level% Example response

```json
%response_example%
```

</docs-EndpointExample>
</template>
</docs-Endpoint>
