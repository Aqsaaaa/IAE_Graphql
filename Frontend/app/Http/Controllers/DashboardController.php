<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $graphqlEndpointDeliveries = 'http://localhost:4001/graphql';
    private $graphqlEndpointVendor = 'http://localhost:4003/graphql';
    private $graphqlEndpointMemberships = 'http://localhost:4002/graphql';

    public function index()
    {
        return view('dashboard.index');
    }

    private function graphqlQuery(string $endpoint, string $query, array $variables = [])
    {
        $response = Http::post($endpoint, [
            'query' => $query,
            'variables' => $variables,
        ]);

        return $response->json();
    }

    public function getVendorRequests()
{
    $query = <<<GQL
    query {
        getAllVendorRequests{
            vendor_id
            status
            id
            ingredient_id
            quantity
            requested_at
            estimated_arrival
        }
    }
    GQL;

    $data = $this->graphqlQuery($this->graphqlEndpointVendor, $query);
    return response()->json($data['data']['getAllVendorRequests'] ?? []);
}

public function getDeliveries()
{
    $query = <<<GQL
    query {
        getAllDeliveries {
            id
            order_id
            delivery_status
            delivery_time
            current_location
        }
    }
    GQL;

    $data = $this->graphqlQuery($this->graphqlEndpointDeliveries, $query);
    return response()->json($data['data']['getAllDeliveries'] ?? []);
}

public function getMemberships()
{
    $query = <<<GQL
    query {
        getAllMemberships {
            id
            user_id
            points
            user {
                id
                name
                phone
            }
        }
    }
    GQL;

    $data = $this->graphqlQuery($this->graphqlEndpointMemberships, $query);
    return response()->json($data['data']['getAllMemberships'] ?? []);
}

public function createVendorRequest(Request $request)
{
    $mutation = <<<GQL
    mutation CreateVendorRequest(\$input: VendorRequestInput!) {
        createVendorRequest(input: \$input) {
            id
        }
    }
    GQL;

    $variables = [
        'input' => [
            'vendor_id' => $request->vendor_id,
            'ingredient_id' => $request->ingredient_id,
            'quantity' => (int) $request->quantity,
            'status' => $request->status,
            'requested_at' => $request->requested_at,
            'estimated_arrival' => $request->estimated_arrival,
        ]
    ];

    $response = $this->graphqlQuery($this->graphqlEndpointVendor, $mutation, $variables);
    return response()->json($response['data']['createVendorRequest'] ?? []);
}
public function createDelivery(Request $request)
{
    $mutation = <<<GQL
    mutation CreateDelivery(\$input: DeliveryInput!) {
        createDelivery(input: \$input) {
            id
        }
    }
    GQL;

    $variables = [
        'input' => [
            'order_id' => $request->order_id,
            'delivery_status' => $request->delivery_status,
            'delivery_time' => $request->delivery_time,
            'current_location' => $request->current_location,
        ]
    ];

    $response = $this->graphqlQuery($this->graphqlEndpointDeliveries, $mutation, $variables);
    return response()->json($response['data']['createDelivery'] ?? []);
}
public function createMembership(Request $request)
{
    $mutation = <<<GQL
    mutation CreateMembership(\$input: MembershipInput!) {
        createMembership(input: \$input) {
            id
        }
    }
    GQL;

    $variables = [
        'input' => [
            'user_id' => $request->user_id,
            'points' => (int) $request->points,
        ]
    ];

    $response = $this->graphqlQuery($this->graphqlEndpointMemberships, $mutation, $variables);
    return response()->json($response['data']['createMembership'] ?? []);
}

}
