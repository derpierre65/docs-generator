<template>
	<table>
		<thead>
		<tr>
			<th>Status Code</th>
			<th>Description</th>
		</tr>
		</thead>
		<tbody>
		<tr><td><code>200</code></td><td>OK</td></tr>
		<tr><td><code>304</code></td><td>Not modified</td></tr>
		<tr>
			<td><code>401</code></td>
			<td>
				Requires authentication
				<ul>
					<li>The ID in the Client-Id header must match the Client ID in the access token.</li>
					<li>The Authorization header is required and must specify an app access token or user access token.</li>
					<li>The access token is not valid.</li>
					<li v-if="requireScope.length">
						<span>The user access token must include the following scopes:</span>
						<div class="pl-1 inline space-x-1">
							<code v-for="scope of requireScope">{{scope}}</code>
						</div>
					</li>
					<li v-if="anyScope.length">
						<span>The user access token must include one of the following scopes:</span>
						<div class="pl-1 inline space-x-1">
							<code v-for="scope of anyScope">{{scope}}</code>
						</div>
					</li>
				</ul>
			</td>
		</tr>
		<tr><td><code>404</code></td><td>Resource Not Found</td></tr>
		<tr><td><code>422</code></td><td>Unprocessable Entity</td></tr>
		</tbody>
	</table>
</template>

<script>
export default {
	name: 'HttpResponseCodes',
	props: {
		requireScope: {
			type: Array,
			default: () => [],
		},
		anyScope: {
			type: Array,
			default: () => [],
		},
	}
};
</script>