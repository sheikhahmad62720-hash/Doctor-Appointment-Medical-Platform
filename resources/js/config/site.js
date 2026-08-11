// ============================================================
//  SITE CONTENT — edit this file to update the public website.
//  Changes to doctor/clinics/reviews apply instantly on refresh.
//  After changing services or pricing, also run: php artisan db:seed
// ============================================================
export default {
    "doctor": {
        "name": "Prof. Dr. Awais Malik",
        "specialization": "Bariatric, Laparoscopic & General Surgeon",
        "bio": "Prof. Dr. Awais Malik is a Professor of Surgery at Fatima Memorial Hospital, Lahore, with more than 10 years of surgical experience. He specialises in bariatric (weight loss) surgery, advanced laparoscopic procedures and complex general surgery — combining technical precision with calm, patient-first care.",
        "qualifications": "MBBS\nFCPS (General Surgery)",
        "education": "Fatima Memorial Hospital, Lahore",
        "experience_years": 10,
        "consultation_fee": 3000,
        "rating": 4.9,
        "phone": "+92 300 1234567",
        "email": "care@medicare.test",
        "location": "Fatima Memorial Hospital, Shadman, Lahore",
        "patients_count": "8k+",
        "reviews_count": "2,340",
        "satisfaction": 97,
        "available": true,
        "clinics": [
            {
                "name": "Fatima Memorial Hospital (FMH)",
                "area": "Shadman",
                "city": "Lahore",
                "timing": "Morning",
                "address": "Fatima Memorial Hospital, Shadman, Lahore"
            },
            {
                "name": "Mid City Hospital",
                "area": "Jail Road",
                "city": "Lahore",
                "timing": "Evening",
                "address": "Mid City Hospital, Jail Road, Lahore"
            }
        ]
    },
    "services": [
        {
            "id": 1,
            "name": "Bariatric Surgery (Weight Loss)",
            "slug": "bariatric-surgery",
            "description": "Surgical weight-loss solutions for obesity and metabolic diseases.",
            "procedures": [
                "Sleeve Gastrectomy",
                "Gastric Bypass",
                "Treatment of obesity & metabolic diseases"
            ],
            "icon": "scale",
            "duration_minutes": 45,
            "price": 3000,
            "consultation_type": "physical",
            "is_active": true,
            "sort_order": 1
        },
        {
            "id": 2,
            "name": "Laparoscopic Surgery (Minimally Invasive)",
            "slug": "laparoscopic-surgery",
            "description": "Minimally invasive surgery through tiny incisions — less pain, faster recovery and minimal scarring.",
            "procedures": [
                "Gallbladder (gall stone) surgery",
                "Hernia repair (TAPP technique)",
                "Appendix surgery",
                "Acid reflux / hiatal hernia (Fundoplication)"
            ],
            "icon": "shield",
            "duration_minutes": 45,
            "price": 2500,
            "consultation_type": "physical",
            "is_active": true,
            "sort_order": 2
        },
        {
            "id": 3,
            "name": "General Surgery",
            "slug": "general-surgery",
            "description": "Comprehensive surgical care for colorectal, thyroid and breast conditions.",
            "procedures": [
                "Colorectal surgery (piles & fissures)",
                "Thyroid surgery",
                "Breast surgery"
            ],
            "icon": "scissors",
            "duration_minutes": 30,
            "price": 2000,
            "consultation_type": "physical",
            "is_active": true,
            "sort_order": 3
        }
    ],
    "reviews": [
        {
            "id": 1,
            "patient": "Ayesha Khan",
            "rating": 5,
            "comment": "Prof. Dr. Awais Malik is incredibly thorough and compassionate. He took the time to explain everything clearly. Highly recommended.",
            "date": "Jul 2026"
        },
        {
            "id": 2,
            "patient": "Ali Raza",
            "rating": 5,
            "comment": "Excellent surgeon. My laparoscopic surgery went smoothly and recovery was quick.",
            "date": "Jun 2026"
        },
        {
            "id": 3,
            "patient": "Fatima Malik",
            "rating": 5,
            "comment": "Very professional and caring. The clinic is clean and the staff are friendly.",
            "date": "Jun 2026"
        },
        {
            "id": 4,
            "patient": "Hassan Ahmed",
            "rating": 4,
            "comment": "Great experience from booking to consultation. Very efficient process.",
            "date": "May 2026"
        },
        {
            "id": 5,
            "patient": "Sana Tariq",
            "rating": 5,
            "comment": "Prof. Malik helped me recover from my surgery better than anyone before. Truly grateful.",
            "date": "May 2026"
        },
        {
            "id": 6,
            "patient": "Usman Chaudhry",
            "rating": 5,
            "comment": "Knowledgeable, patient and kind. Booking an appointment was very easy.",
            "date": "Apr 2026"
        },
        {
            "id": 7,
            "patient": "Zainab Ali",
            "rating": 4,
            "comment": "Quick appointment, clear advice and reasonable pricing.",
            "date": "Apr 2026"
        },
        {
            "id": 8,
            "patient": "Bilal Ahmed",
            "rating": 5,
            "comment": "A surgeon who genuinely listens. My follow-up care has been excellent.",
            "date": "Mar 2026"
        }
    ]
}
