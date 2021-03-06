var config = {
    lang: 'en',
    time: {
        timeFormat: 24,
        displaySeconds: true,
        digitFade: false,
    },
    weather: {
        //change weather params here:
        //units: metric or imperial
        params: {
            q: 'Gothenburg, Sweden',
            units: 'metric',
            // if you want a different lang for the weather that what is set above, change it here
            lang: 'sv',
            APPID: '78e5c0b304dfb3121f482a7f5f813ceb'
        }
    },
    compliments: {
        interval: 10000,
        fadeInterval: 4000,
        morning: [
            'Good morning, handsome!',
            'Tjena snygging!',
            'Looking hot today, sir!!'
        ],
        afternoon: [
            'Hello, beauty!',
            'You look sexy!',
            'Looking good today!'
        ],
        evening: [
            'Wow, you look hot!',
            'You look nice!',
            'Hi, sexy!'
        ]
    },
    calendar: {
        maximumEntries: 10, // Total Maximum Entries
		displaySymbol: true,
		defaultSymbol: 'calendar', // Fontawsome Symbol see http://fontawesome.io/cheatsheet/
        urls: [
		{
			symbol: 'calendar-plus-o',
			url: 'https://p01-calendarws.icloud.com/ca/subscribe/1/n6x7Farxpt7m9S8bHg1TGArSj7J6kanm_2KEoJPL5YIAk3y70FpRo4GyWwO-6QfHSY5mXtHcRGVxYZUf7U3HPDOTG5x0qYnno1Zr_VuKH2M'
		},
		{
			symbol: 'soccer-ball-o',
			url: 'https://www.google.com/calendar/ical/akvbisn5iha43idv0ktdalnor4%40group.calendar.google.com/public/basic.ics',
		},
		{
			symbol: 'tv',
            url: 'http://showrss.info/ical.php?user_id=284017',
		},
        {
			symbol: 'calendar-plus-o',
			url: 'https://calendar.google.com/calendar/ical/fredrik.safsten%40gmail.com/public/basic.ics'
		},
		// {
			// symbol: 'mars',
			// url: "https://server/url/to/his.ics",
		// },
		// {
			// symbol: 'venus',
			// url: "https://server/url/to/hers.ics",
		// },
		// {
			// symbol: 'venus-mars',
			// url: "https://server/url/to/theirs.ics",
		// },
		]
    },
    news: {
        feed: 'http://www.expressen.se/rss/nyheter/'
    }
}
