// It will be wordpress post table
const trips = {
    id: 33,
    trip_code: '#2363',

    duration: {
        type: "days/hour",
        duration: 4
    },
    // Nights only applicable when duration is days / not hour
    nights: {
        type: "nights",
        duration: 2,
    },

    cut_time: {
        enable: "yes",
        start_of_date: "2023-11-12",
        cut_off_date: "2023-11-12",

    },

    min_max_age: {
        enable: "yes",
        min_age: 2,
        max_age: 44
    },

    // min_max_participents: {
    //     enable: "yes",
    //     min: 2,
    //     max: 44
    // },

    max_traveler: 44,
    trip_type: "Group",
    trip_category: "Adventure/Religious/Leisure/Regular",
    trip_status: "available to booking/booking closed/booking full/booking not started/booking not available",
}

const trip_packages = {
    id: 1,
    title: "Trip package name : Ex: Golden/ Regular",
    available_booking_date: {
        enable: "yes",
        start_date: "2023-11-12",
        end_date: "2023-11-12"
    },

    package_quantity: {
        enable: "yes",
        quantity: 44
    },

    pricing: [
        {
            adult: {
                enable: "yes",
                label: "Adult",
                price: 500,
                pricing_type: "per_person/group" ,// Should research about group
                selling_price: {
                    enable: "yes",
                    price: 400
                },
                min_pax: 3,
                max_pax: 5,
            },
            child: {
                enable: "yes",
                label: "Adult",
                price: 500,
                pricing_type: "per_person/group" ,// Should research about group
                selling_price: {
                    enable: "yes",
                    price: 400
                },
                min_pax: 3,
                max_pax: 5,
            }
        }
    ]
}

const trip_description = {
    id: 1,
    title: "Trip description title",
    trip_slug: "trip-slug",
    description: "Trip description",
    trip_highlights: {
        title: "Highlights",
        options: ["Dinner", "Car rent", "Manali"]
    }
}

const trip_itinerary =  {
    id: 1,
    title: "Trip itinerary title",
    options: [
        {
            title: "Day 1",
            description: "itinerary description",
        },
        {
            label: "Day 2",
            value: "Going to kashmir"
        }
    ]
}

const trip_inc_exc = {
    section_title : "Enter the cost tab section title",
    includes: {
        title: "Cost Includes Title",
        services: ["Dinner", "Breakfast"]
    },
    excludes: {
        title: "Cost excludes Title",
        services: ["Dinner", "Breakfast"]
    },
}

const trip_info = {
    title: "section title",
    options: [{ label: "WIFI", value: "no" }, { label: "Admission fee", value: "Now not here" }]
}


const trip_gallery = {
    enable_image_gallery: "yes",
    images: [{image_link: "image_link", alt: "text"}],
    enable_video_gallery: "yes",
    videos: [{video_link: "image_link", alt: "text"}]
}

const trip_map =  {
    id: 11,
    title: "Map section title",
    map_image: { image_link:"ashjas", alt: "sadkjas"},
    map_iframe_code: { link:"ashjas", alt: "sadkjas"}
}

const trip_FAQS = {
    id: 11,
    title: "Map section title",
    FAQ: [
        {
            question: "Question",
            answer: "Answer"
        }
    ]
}

const trip_reviews = {
    id: 11,
    title: "Map section title",
    reviews: [
        {
            name: "Name",
            review: "Review",
            rating: 4
        }
    ]
}

// Other tables that is related to trip
const destinations = {
    id:1,
    name: "Destination name",
    slug: "destination-slug",
    description: "Destination description",
    image: { image_link: "", alt: ""},
}

const attributes = {
    id: 1,
    name: "Attribute name",
    slug: "attribute-slug",
    description: "Attribute description",
    icon: { image_link: "", alt: ""},
}

const travel_categories = {
    id: 1,
    name: "Category name",
    slug: "category-slug",
    description: "Category description",
    icon: { image_link: "", alt: ""},
}

const activities = {
    id: 1,
    name: "Activity name",
    slug: "activity-slug",
    description: "Activity description",
    icon: { image_link: "", alt: ""},
}

const deficulty_levels = {
    id: 1,
    name: "Deficulty level name",
    slug: "deficulty-level-slug",
    description: "Deficulty level description",
    icon: { image_link: "", alt: ""},

}


const pricing_categories = {
    id: 1,
    name: "Pricing category name",
    slug: "pricing-category-slug",
    description: "Pricing category description",
    icon: { image_link: "", alt: ""},
}




// Settings Page Start

const trip_info_settings = {
    id: 1,
    options: [{
        field_name: "Field name",
        field_icon: "icon_name",
        field_type: "text/number/textarea/select/duration",
        field_placeholder: "Field placeholder",
    }]
}
// By deafult Overview, Itenary, Map, FAQs, Gallery, Cost 
const trip_tabs_settings = {
    id: 1,
    tabs: [{
        tab_name: "Tab name",
        tab_icon: "icon_name",
        enable: "yes",
    }]
}
